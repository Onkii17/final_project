<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="font-[Poppins] bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900">
    <!-- Navigation bar -->
    <nav class="bg-white dark:bg-gray-800 shadow-lg animate__animated animate__fadeInDown">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex space-x-7">
                    <div>
                        <a href="index.php" class="flex items-center py-4 px-2">
                            <span class="font-semibold text-gray-700 dark:text-gray-300 text-lg">Feedback System</span>
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="index.php" class="py-4 px-2 text-blue-600 border-b-4 border-blue-600 font-semibold">Home</a>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="semester.php" class="py-4 px-2 text-gray-600 dark:text-gray-300 font-semibold hover:text-blue-600 transition duration-300">Give Feedback</a>
                        <?php if(isset($_SESSION['email']) && $_SESSION['email'] === 'admin@example.com'): ?>
                            <a href="admin/dashboard.php" class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300"><i class="bi bi-speedometer2"></i> Admin Panel</a>
                        <?php endif; ?>
                        <a href="logout.php" class="py-4 px-2 text-gray-600 dark:text-gray-300 font-semibold hover:text-blue-600 transition duration-300">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="py-4 px-2 text-gray-600 dark:text-gray-300 font-semibold hover:text-blue-600 transition duration-300">Login</a>
                        <a href="signup.php" class="py-4 px-2 text-gray-600 dark:text-gray-300 font-semibold hover:text-blue-600 transition duration-300">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="flex flex-col md:flex-row items-center justify-between max-w-6xl mx-auto px-4 py-12 animate__animated animate__fadeIn">
        <div class="md:w-1/2 mb-10 md:mb-0">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white leading-tight mb-6">
                Share Your <span class="text-blue-600 dark:text-blue-400">Feedback</span><br>Help Improve Education
            </h1>
            <p class="text-gray-600 dark:text-gray-300 text-lg mb-8">
                A platform for students to provide honest feedback about their teachers and courses.
            </p>
            <div class="flex space-x-4">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="semester.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">
                        Give Feedback
                    </a>
                <?php else: ?>
                    <a href="login.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">
                        Get Started
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="md:w-1/2">
            <img src="final-removebg-preview.png" alt="Feedback Illustration" class="w-full h-auto animate__animated animate__fadeInRight">
        </div>
    </div>
</body>
</html>
