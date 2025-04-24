<?php
require_once '../includes/db.php';

$pdo = (new Database())->getConnection();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];
    $subject = $_POST['subject'];
    $profile_image = null; // No image handling now

    try {
        // Insert into teachers
        $stmt = $pdo->prepare("INSERT INTO teachers (name, email, department, semester, profile_image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $department, $semester, $profile_image]);
        $teacher_id = $pdo->lastInsertId();

        // Check if subject already exists
        $subject_check = $pdo->prepare("SELECT id FROM subjects WHERE name = ? AND semester = ?");
        $subject_check->execute([$subject, $semester]);
        $subject_data = $subject_check->fetch();

        if ($subject_data) {
            $subject_id = $subject_data['id'];
        } else {
            // Insert new subject
            $subject_stmt = $pdo->prepare("INSERT INTO subjects (name, semester) VALUES (?, ?)");
            $subject_stmt->execute([$subject, $semester]);
            $subject_id = $pdo->lastInsertId();
        }

        // Insert into teacher_subjects
        $link_stmt = $pdo->prepare("INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES (?, ?)");
        $link_stmt->execute([$teacher_id, $subject_id]);

        header("Location: success.php?msg=Teacher profile added successfully.");
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Teacher</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-indigo-300 via-purple-300 to-pink-300 min-h-screen flex items-center justify-center font-roboto">

    <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md transform transition-transform hover:scale-105 hover:shadow-2xl animate__animated animate__fadeInUp">
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-700">➕ Add New Teacher</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded-lg shadow-lg animate__animated animate__fadeIn animate__delay-1s"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" class="space-y-6">
            <input type="text" name="name" placeholder="Full Name" class="w-full border p-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" required />
            
            <input type="email" name="email" placeholder="Email" class="w-full border p-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" required />
            
            <input type="text" name="department" placeholder="Department" class="w-full border p-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" required />
            
            <select name="semester" class="w-full border p-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" required>
                <option value="">Select Semester</option>
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
            
            <input type="text" name="subject" placeholder="Subject Name" class="w-full border p-3 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition" required />

            <button class="w-full bg-indigo-600 text-white p-3 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 transition transform hover:scale-105">Add Teacher</button>
        </form>
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate__fadeInUp { animation: fadeInUp 0.7s ease-out; }
        .animate__fadeIn { animation: fadeIn 0.5s ease-in-out; }
        .animate__delay-1s { animation-delay: 1s; }
        .animate__delay-2s { animation-delay: 2s; }
    </style>

</body>
</html>
