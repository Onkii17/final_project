<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = new Auth();
    
    $student_id = trim($_POST['student_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if($auth->register($student_id, $first_name, $last_name, $email, $password)) {
        $_SESSION['success'] = "Registration successful! Please login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed. Email may already exist.";
        header("Location: signup.php");
        exit();
    }
} else {
    header("Location: signup.php");
    exit();
}
?>