<?php
session_start();  // Start session to handle session variables
require_once '../includes/db.php';

$pdo = (new Database())->getConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $pdo->prepare("SELECT * FROM teachers WHERE email = ?");
    $stmt->execute([$email]);
    $teacher = $stmt->fetch();

    if ($teacher) {
        $_SESSION['teacher_id'] = $teacher['id'];
        header("Location: profile.php");
        exit;
    } else {
        $error = "Invalid email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- animate.css for smooth entry -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .custom-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
    </style>
</head>
<body class="flex justify-center items-center h-screen bg-gradient-to-br from-indigo-300 via-purple-300 to-pink-300">

    <form method="post" class="w-full max-w-sm bg-white p-8 rounded-xl shadow-xl custom-fade-in">
        <h2 class="text-2xl font-bold mb-6 text-center text-purple-600">👩‍🏫 Teacher Login</h2>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 animate__animated animate__shakeX">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" placeholder="Enter your email"
                   class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                   required />
        </div>

        <button class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition transform hover:scale-105">
            Login
        </button>
    </form>

</body>
</html>
