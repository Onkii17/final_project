<?php
// session_start();  // Uncomment if needed
// require_once '../includes/db.php';

$success = isset($_GET['success']) && $_GET['success'] === 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto text-center">
        <?php if ($success): ?>
            <h1 class="text-3xl font-bold mb-4 text-green-600">🎉 Registration Successful!</h1>
            <p class="text-lg">Your teacher profile has been successfully created. You can now log in and access your profile.</p>
            <a href="login.php" class="mt-4 inline-block text-blue-600 underline">Go to Login Page</a>
        <?php else: ?>
            <h1 class="text-3xl font-bold mb-4 text-red-600">⚠️ Registration Failed</h1>
            <p class="text-lg">There was an issue with the registration. Please try again.</p>
        <?php endif; ?>
    </div>
</body>
</html>
