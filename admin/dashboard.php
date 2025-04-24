<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$pdo = (new Database())->getConnection();

// Delete feedback
if (isset($_POST['delete_feedback'])) {
    $pdo->exec("DELETE FROM feedback");
    header("Location: dashboard.php");
    exit;
}

// Delete teacher with FK cleanup
if (isset($_POST['delete_teacher'])) {
    $teacher_id = $_POST['teacher_id'];
    $pdo->prepare("DELETE FROM teacher_subjects WHERE teacher_id = ?")->execute([$teacher_id]);
    $pdo->prepare("DELETE FROM teachers WHERE id = ?")->execute([$teacher_id]);
    header("Location: dashboard.php");
    exit;
}

// Filters
$semesters = $pdo->query("SELECT DISTINCT semester FROM subjects ORDER BY semester")->fetchAll(PDO::FETCH_COLUMN);
$subjects = $pdo->query("SELECT DISTINCT name FROM subjects ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);

$filter_sql = "";
$params = [];

if (!empty($_GET['semester'])) {
    $filter_sql .= " AND s.semester = ?";
    $params[] = $_GET['semester'];
}
if (!empty($_GET['subject'])) {
    $filter_sql .= " AND s.name = ?";
    $params[] = $_GET['subject'];
}

// Total feedback submissions
$total_feedback = $pdo->query("SELECT COUNT(DISTINCT student_id) FROM feedback")->fetchColumn();

// Avg score per teacher & comments
$avgStmt = $pdo->prepare("
    SELECT t.id, t.name, 
           ROUND(AVG((q1+q2+q3+q4+q5+q6+q7+q8+q9+q10)/10), 2) AS avg_score,
           GROUP_CONCAT(DISTINCT f.comments SEPARATOR ' | ') AS reviews
    FROM feedback f
    JOIN teachers t ON f.teacher_id = t.id
    JOIN teacher_subjects ts ON t.id = ts.teacher_id
    JOIN subjects s ON ts.subject_id = s.id
    WHERE 1=1 $filter_sql
    GROUP BY f.teacher_id
    ORDER BY avg_score DESC
");

$avgStmt->execute($params);
$teacher_averages = $avgStmt->fetchAll(PDO::FETCH_ASSOC);

// If no feedback is available, show teachers with zero feedback
if (empty($teacher_averages)) {
    $teacher_averages = $pdo->query("SELECT id, name, 0 as avg_score, '' as reviews FROM teachers ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
}

$good_teachers = array_filter($teacher_averages, fn($t) => $t['avg_score'] >= 8 && $t['avg_score'] > 0);

$bad_teachers = array_filter($teacher_averages, fn($t) => $t['avg_score'] < 5 && $t['avg_score'] > 0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-b from-blue-100 to-white p-6 font-sans fade-in">

    <h1 class="text-4xl font-extrabold mb-6 text-blue-800 animate-pulse">📊 Admin Dashboard</h1>

    <form method="get" class="flex flex-wrap gap-4 mb-6 fade-in">
        <select name="semester" class="border p-2 rounded shadow-md hover:shadow-lg transition">
            <option value="">All Semesters</option>
            <?php foreach ($semesters as $sem): ?>
                <option value="<?= $sem ?>" <?= ($_GET['semester'] ?? '') == $sem ? 'selected' : '' ?>>Semester <?= $sem ?></option>
            <?php endforeach; ?>
        </select>
        <select name="subject" class="border p-2 rounded shadow-md hover:shadow-lg transition">
            <option value="">All Subjects</option>
            <?php foreach ($subjects as $sub): ?>
                <option value="<?= $sub ?>" <?= ($_GET['subject'] ?? '') == $sub ? 'selected' : '' ?>><?= $sub ?></option>
            <?php endforeach; ?>
        </select>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 hover:scale-105 transition transform">🔍 Filter</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 fade-in">
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition transform hover:scale-105 text-center">
            <h2 class="text-xl font-semibold text-gray-700">📥 Total Feedback</h2>
            <p class="text-4xl text-green-600 font-bold mt-2"><?= $total_feedback ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition transform hover:scale-105 text-center">
            <h2 class="text-xl font-semibold text-gray-700">🌟 Good Reviews</h2>
            <p class="text-4xl text-blue-600 font-bold mt-2"><?= count($good_teachers) ?></p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition transform hover:scale-105 text-center">
            <h2 class="text-xl font-semibold text-gray-700">⚠️ Bad Reviews</h2>
            <p class="text-4xl text-red-600 font-bold mt-2"><?= count($bad_teachers) ?></p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md fade-in mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">📚 Teacher Feedback Averages</h2>
            <div class="flex gap-3">
                <form method="post">
                    <button name="delete_feedback" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 hover:scale-105 transition">🗑️ Delete Feedback</button>
                </form>
                <button onclick="exportCSV()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 hover:scale-105 transition">📤 Export CSV</button>
            </div>
        </div>
        <table class="w-full table-auto border shadow-sm" id="feedbackTable">
            <thead class="bg-blue-100">
                <tr>
                    <th class="p-3 text-left">👨‍🏫 Teacher</th>
                    <th class="p-3 text-left">📊 Avg Score</th>
                    <th class="p-3 text-left">📝 Review</th>
                    <th class="p-3 text-left">📈 Status</th>
                    <th class="p-3 text-left">🗑️ Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teacher_averages as $t): ?>
                    <tr class="border-b hover:bg-blue-50 transition">
                        <td class="p-3"><?= htmlspecialchars($t['name']) ?></td>
                        <td class="p-3"><?= $t['avg_score'] ?>/10</td>
                        <td class="p-3 text-sm text-gray-600"><?= htmlspecialchars($t['reviews']) ?></td>
                        <td class="p-3">
                            <?php if ($t['avg_score'] >= 8): ?>
                                <span class="text-green-600 font-semibold">Excellent</span>
                            <?php elseif ($t['avg_score'] < 5): ?>
                                <span class="text-red-600 font-semibold">Needs Improvement</span>
                            <?php else: ?>
                                <span class="text-yellow-600 font-semibold">Average</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-3">
                            <form method="post" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                <input type="hidden" name="teacher_id" value="<?= $t['id'] ?>">
                                <button type="submit" name="delete_teacher" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md fade-in">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">📈 Performance Chart</h2>
        <canvas id="feedbackChart" height="100"></canvas>
    </div>

    <script>
        const chart = new Chart(document.getElementById('feedbackChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($teacher_averages, 'name')) ?>,
                datasets: [{
                    label: 'Avg Feedback Score',
                    data: <?= json_encode(array_column($teacher_averages, 'avg_score')) ?>,
                    backgroundColor: '#3b82f6',
                    borderRadius: 8
                }]
            },
            options: {
                scales: {
                    y: {
                        suggestedMin: 0,
                        suggestedMax: 10
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutBounce'
                }
            }
        });

        function exportCSV() {
            let table = document.getElementById("feedbackTable");
            let rows = [...table.rows];
            let csv = rows.map(r => [...r.cells].map(c => `"${c.innerText}"`).join(",")).join("\n");
            let blob = new Blob([csv], { type: 'text/csv' });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "teacher_feedback.csv";
            link.click();
        }
    </script>
</body>
</html>
