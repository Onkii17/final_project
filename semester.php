<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Year Selection | Student Feedback System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
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
    <!-- Navigation -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Year Selection -->
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-4xl w-full bg-white p-8 rounded-xl shadow-xl animate__fadeIn">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 animate__fadeIn">Select Your Year</h2>
                <p class="text-gray-600 mt-2 animate__fadeIn">Choose the academic year for which you want to provide feedback</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate__fadeIn">
                <!-- First Year -->
                <div class="bg-blue-50 p-6 rounded-xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-2 cursor-pointer" 
                     onclick="window.location.href='teachers.php?year=fy'">
                    <div class="text-center">
                        <div class="mx-auto bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mb-4">
                            <span class="text-blue-600 text-2xl font-bold">FY</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">First Year</h3>
                        <p class="text-gray-600">Provide feedback for your First Year courses and teachers.</p>
                    </div>
                </div>
                
                <!-- Second Year -->
                <div class="bg-indigo-50 p-6 rounded-xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-2 cursor-pointer" 
                     onclick="window.location.href='teachers.php?year=sy'">
                    <div class="text-center">
                        <div class="mx-auto bg-indigo-100 w-20 h-20 rounded-full flex items-center justify-center mb-4">
                            <span class="text-indigo-600 text-2xl font-bold">SY</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Second Year</h3>
                        <p class="text-gray-600">Provide feedback for your Second Year courses and teachers.</p>
                    </div>
                </div>
                
                <!-- Third Year -->
                <div class="bg-purple-50 p-6 rounded-xl shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-2 cursor-pointer" 
                     onclick="window.location.href='teachers.php?year=ty'">
                    <div class="text-center">
                        <div class="mx-auto bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mb-4">
                            <span class="text-purple-600 text-2xl font-bold">TY</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Third Year</h3>
                        <p class="text-gray-600">Provide feedback for your Third Year courses and teachers.</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 flex justify-between">
                <a href="index.php" class="text-blue-600 hover:text-blue-500 font-medium">Back to Home</a>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
