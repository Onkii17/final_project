<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = new Auth();
    
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if($auth->login($email, $password)) {
        header("Location: semester.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: semester.php");
    exit();
}
?>