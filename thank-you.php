<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You | Feedback System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'zoom-in': 'zoomIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-poppins bg-gradient-to-r from-blue-50 to-indigo-50">

    <div class="min-h-screen flex items-center justify-center p-4 animate__animated animate__fadeIn">
        <div class="bg-white p-8 rounded-xl shadow-xl text-center max-w-md w-full transform hover:scale-105 transition-all duration-300 ease-in-out animate__animated animate__zoomIn">
            <div class="text-green-500 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto animate__animated animate__bounceIn" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-4 animate__animated animate__fadeIn">Thank You!</h1>
            <p class="text-gray-600 mb-6 animate__animated animate__fadeIn animate__delay-1s">Your feedback has been submitted successfully.</p>
            <div class="space-y-3">
                <a href="semester.php" 
                   class="block bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 animate__animated animate__fadeIn">
                   Submit More Feedback
                </a>
                <a href="index.php" 
                   class="block border border-blue-600 text-blue-600 py-3 px-6 rounded-lg hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 animate__animated animate__fadeIn animate__delay-1s">
                   Return to Home
                </a>
            </div>
        </div>
    </div>

</body>
</html>
