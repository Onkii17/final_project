<?php
$msg = $_GET['msg'] ?? 'Operation completed successfully.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Include animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gradient-to-br from-indigo-200 via-purple-200 to-pink-200 min-h-screen flex items-center justify-center font-roboto">

    <div class="max-w-4xl mx-auto text-center bg-white p-8 rounded-xl shadow-xl transform transition-transform hover:scale-105 hover:shadow-2xl animate__animated animate__fadeInUp">
        <h2 class="text-3xl font-bold text-green-600 animate__animated animate__fadeIn animate__delay-1s">✅ Success</h2>
        <p class="text-lg mb-6 animate__animated animate__fadeIn animate__delay-2s"><?= htmlspecialchars($msg) ?></p>
        <a href="add_teacher.php" class="inline-block text-blue-600 underline text-lg font-semibold hover:text-blue-800 transition duration-200 transform hover:scale-105 animate__animated animate__fadeIn animate__delay-3s">➕ Add Another</a>
    </div>

    <style>
        /* Animation styles */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate__fadeInUp {
            animation: fadeInUp 0.7s ease-out;
        }

        .animate__fadeIn {
            animation: fadeIn 0.5s ease-in-out;
        }

        .animate__delay-1s {
            animation-delay: 1s;
        }

        .animate__delay-2s {
            animation-delay: 2s;
        }

        .animate__delay-3s {
            animation-delay: 3s;
        }
    </style>

</body>
</html>
