<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $feedback = [
        'student_id' => $_SESSION['user_id'],
        'teacher_id' => intval($_POST['teacher_id']),
        'semester' => intval($_POST['semester']),
        'q1' => intval($_POST['q1']),
        'q2' => intval($_POST['q2']),
        'q3' => intval($_POST['q3']),
        'q4' => intval($_POST['q4']),
        'q5' => intval($_POST['q5']),
        'q6' => intval($_POST['q6']),
        'q7' => intval($_POST['q7']),
        'q8' => intval($_POST['q8']),
        'q9' => intval($_POST['q9']),
        'q10' => intval($_POST['q10']),
        'comments' => $_POST['comments'] ?? ''
    ];

    $stmt = $db->prepare("
        INSERT INTO feedback 
        (student_id, teacher_id, semester, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, comments)
        VALUES 
        (:student_id, :teacher_id, :semester, :q1, :q2, :q3, :q4, :q5, :q6, :q7, :q8, :q9, :q10, :comments)
    ");
    
    if($stmt->execute($feedback)) {
        header("Location: thank-you.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to submit feedback. Please try again.";
        header("Location: feedback.php?teacher=".$feedback['teacher_id']."&semester=".$feedback['semester']);
        exit();
    }
} else {
    header("Location: semester.php");
    exit();
}
?>