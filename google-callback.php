<?php
session_start();
require_once 'includes/dbconn.inc.php';
require_once 'vendor/autoload.php'; // Composer autoload for Google API Client

// Google OAuth configuration
$googleClientId = "YOUR_GOOGLE_CLIENT_ID"; // Replace with actual client ID
$googleClientSecret = "YOUR_GOOGLE_CLIENT_SECRET"; // Replace with actual client secret
$googleRedirectUri = "https://yourdomain.com/google-callback.php"; // Replace with actual redirect URI

// Initialize the Google Client
$client = new Google_Client();
$client->setClientId($googleClientId);
$client->setClientSecret($googleClientSecret);
$client->setRedirectUri($googleRedirectUri);
$client->addScope("email");
$client->addScope("profile");

// Handle the OAuth 2.0 server response
if (isset($_GET['code'])) {
    // Exchange authorization code for access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);
    
    // Get user profile
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();
    
    // Extract user data
    $googleId = $userInfo->getId();
    $email = $userInfo->getEmail();
    $name = $userInfo->getName();
    
    try {
        // Check if user exists with this Google ID
        $stmt = $conn->prepare("SELECT * FROM tblUsers WHERE googleID = :googleId");
        $stmt->bindParam(':googleId', $googleId);
        $stmt->execute();
        
        if ($user = $stmt->fetch()) {
            // User exists, log them in
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'] ?? 'User';
            $_SESSION['isAdmin'] = isset($user['isAdmin']) ? (bool)$user['isAdmin'] : false;
            
            header("Location: index.php");
            exit();
        } else {
            // Check if user exists with this email
            $stmt = $conn->prepare("SELECT * FROM tblUsers WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($user = $stmt->fetch()) {
                // User exists with this email, update their Google ID
                $stmt = $conn->prepare("UPDATE tblUsers SET googleID = :googleId WHERE userID = :userId");
                $stmt->bindParam(':googleId', $googleId);
                $stmt->bindParam(':userId', $user['userID']);
                $stmt->execute();
                
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'] ?? 'User';
                $_SESSION['isAdmin'] = isset($user['isAdmin']) ? (bool)$user['isAdmin'] : false;
                
                header("Location: index.php");
                exit();
            } else {
                // Create new user
                $stmt = $conn->prepare("INSERT INTO tblUsers (email, name, googleID, password) VALUES (:email, :name, :googleId, :password)");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':googleId', $googleId);
                
                // Generate a random password for the user (they won't use it, but it's required)
                $randomPassword = password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT);
                $stmt->bindParam(':password', $randomPassword);
                
                $stmt->execute();
                
                $userId = $conn->lastInsertId();
                
                $_SESSION['userID'] = $userId;
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $name;
                $_SESSION['isAdmin'] = false;
                
                header("Location: index.php");
                exit();
            }
        }
    } catch(PDOException $e) {
        // Log error and redirect to login page with error
        error_log('Google login error: ' . $e->getMessage());
        header("Location: login.php?error=sqlerror");
        exit();
    }
} else {
    // Redirect to login page if no code is present
    header("Location: login.php");
    exit();
}
?>