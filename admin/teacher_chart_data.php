<?php
require_once '../includes/db.php';

$pdo = (new Database())->getConnection();
$teacher_id = $_GET['teacher_id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT 
        ROUND(AVG(q1), 2), ROUND(AVG(q2), 2), ROUND(AVG(q3), 2), ROUND(AVG(q4), 2),
        ROUND(AVG(q5), 2), ROUND(AVG(q6), 2), ROUND(AVG(q7), 2), ROUND(AVG(q8), 2),
        ROUND(AVG(q9), 2), ROUND(AVG(q10), 2)
    FROM feedback
    WHERE teacher_id = ?
");
$stmt->execute([$teacher_id]);
$data = $stmt->fetch(PDO::FETCH_NUM);
echo json_encode($data);
