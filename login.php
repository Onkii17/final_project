<?php
//session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: semester.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Student Feedback System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="font-[Poppins] bg-gradient-to-r from-teal-100 to-blue-200 dark:from-gray-800 dark:to-gray-900">
    <div class="min-h-screen flex items-center justify-center px-4 py-12 animate__animated animate__fadeIn animate__delay-1s">
        <div class="max-w-md w-full bg-white dark:bg-gray-700 p-8 rounded-xl shadow-xl transform transition-all duration-500 hover:scale-105 hover:shadow-2xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white transition-all duration-300 ease-in-out">Welcome Back!</h2>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-lg">Login to your account</p>
            </div>
            
            <form action="process_login.php" method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input id="email" name="email" type="email" required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-300 dark:bg-gray-800 dark:text-white"
                        placeholder="student@university.edu">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input id="password" name="password" type="password" required 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition duration-300 dark:bg-gray-800 dark:text-white"
                        placeholder="••••••••">
                </div>
                
                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-300 transform hover:scale-105">
                        Sign in
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Don't have an account? 
                    <a href="signup.php" class="font-medium text-teal-600 hover:text-teal-500 dark:text-teal-400 dark:hover:text-teal-300 transition duration-300 transform hover:scale-105">
                        Sign up
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Animation for the button -->
    <script>
        const button = document.querySelector('button');
        button.addEventListener('mouseover', () => {
            button.classList.add('animate__animated', 'animate__pulse', 'animate__infinite');
        });
        button.addEventListener('mouseout', () => {
            button.classList.remove('animate__animated', 'animate__pulse', 'animate__infinite');
        });
    </script>
</body>
</html>
