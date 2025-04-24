<?php
session_start();  // Ensure session is started to access $_SESSION

// Check if teacher_id is set in session
if (!isset($_SESSION['teacher_id'])) {
    // If not set, redirect to the login page
    header("Location: login.php");
    exit();
}
?>
