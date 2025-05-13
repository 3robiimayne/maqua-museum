<?php
session_start();

if (isset($_POST['register-submit'])) {
    require_once 'dbconn.inc.php';
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordRepeat = $_POST['password-repeat'];
    
    // Check if fields are empty
    if (empty($name) || empty($email) || empty($password) || empty($passwordRepeat)) {
        header("Location: ../register.php?error=emptyfields");
        exit();
    }
    
    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../register.php?error=invalidmail");
        exit();
    }
    
    // Check if passwords match
    if ($password !== $passwordRepeat) {
        header("Location: ../register.php?error=passwordcheck");
        exit();
    }
    
    try {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT email FROM tblUsers WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            header("Location: ../register.php?error=emailtaken");
            exit();
        }
        
        // Insert new user
        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO tblUsers (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPwd);
        $stmt->execute();
        
        // Redirect to login page with success message
        header("Location: ../login.php?success=registered");
        exit();
    } catch(PDOException $e) {
        header("Location: ../register.php?error=sqlerror");
        exit();
    }
} else {
    // If not submitted properly, redirect to register page
    header("Location: ../register.php");
    exit();
}
?>