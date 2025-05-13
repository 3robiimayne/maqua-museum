<?php
session_start();

if (isset($_POST['login-submit'])) {
    require_once 'dbconn.inc.php';
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Check if fields are empty
    if (empty($email) || empty($password)) {
        header("Location: ../login.php?error=emptyfields");
        exit();
    }
    
    try {
        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM tblUsers WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($user = $stmt->fetch()) {
            // Verify password
            $pwdCheck = password_verify($password, $user['password']);
            
            if ($pwdCheck) {
                // Password is correct, create session
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'] ?? 'User';
                
                // Check if user is admin (you would have an isAdmin column in your table)
                $_SESSION['isAdmin'] = isset($user['isAdmin']) ? (bool)$user['isAdmin'] : false;
                
                // Redirect to home page
                header("Location: ../index.php");
                exit();
            } else {
                // Wrong password
                header("Location: ../login.php?error=wrongpassword");
                exit();
            }
        } else {
            // User does not exist
            header("Location: ../login.php?error=nouser");
            exit();
        }
    } catch(PDOException $e) {
        header("Location: ../login.php?error=sqlerror");
        exit();
    }
} else {
    // If not submitted properly, redirect to login page
    header("Location: ../login.php");
    exit();
}
?>