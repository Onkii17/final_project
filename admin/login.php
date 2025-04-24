<?php
session_start();
require_once '../includes/db.php';

$pdo = (new Database())->getConnection();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debug log
    // echo "Username: $username, Password: $password";

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $admin = $stmt->fetch();

    if ($admin) {
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex justify-center items-center h-screen bg-gray-100">
    <form method="post" class="bg-white p-8 rounded-lg shadow-md w-96 space-y-4 animate-fade-in-down">
        <h2 class="text-2xl font-bold text-center text-blue-700">🔐 Admin Login</h2>

        <?php if ($error): ?>
            <p class="text-red-500 text-sm text-center"><?= $error ?></p>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Username" class="w-full border p-2 rounded" required />
        <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded" required />
        <button class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Login</button>
    </form>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.4s ease-out;
        }
    </style>
</body>
</html>
