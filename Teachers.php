<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$year = isset($_GET['year']) ? $_GET['year'] : 'fy';
$semesters = [];

switch($year) {
    case 'fy':
        $semesters = [1, 2];
        break;
    case 'sy':
        $semesters = [3, 4];
        break;
    case 'ty':
        $semesters = [5, 6];
        break;
    default:
        $semesters = [1, 2];
}

$database = new Database();
$db = $database->getConnection();

$placeholders = implode(',', array_fill(0, count($semesters), '?'));
$stmt = $db->prepare("
    SELECT t.id, t.name, GROUP_CONCAT(s.name SEPARATOR ', ') as subjects, s.semester
    FROM teachers t
    JOIN teacher_subjects ts ON t.id = ts.teacher_id
    JOIN subjects s ON ts.subject_id = s.id
    WHERE s.semester IN ($placeholders)
    GROUP BY t.id, s.semester
    ORDER BY s.semester, t.name
");

$stmt->execute($semesters);
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$teachersBySemester = [];
foreach($teachers as $teacher) {
    $semester = $teacher['semester'];
    if(!isset($teachersBySemester[$semester])) {
        $teachersBySemester[$semester] = [];
    }
    $teachersBySemester[$semester][] = $teacher;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Selection | Student Feedback System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-[Poppins] bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900">
    <?php include 'includes/navbar.php'; ?>

    <div class="min-h-screen py-12 px-4">
        <div class="max-w-6xl mx-auto bg-white dark:bg-gray-700 p-8 rounded-xl shadow-xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Select Teachers for Feedback</h2>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    Choose the teachers you want to provide feedback for 
                    <?php echo strtoupper($year); ?> (Semester <?php echo implode(' & ', $semesters); ?>)
                </p>
            </div>
            
            <div class="mb-8">
                <div class="relative max-w-md mx-auto">
                    <input type="text" id="teacherSearch" placeholder="Search teachers..." 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 pl-10 dark:bg-gray-800 dark:text-white">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-300" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <?php foreach($teachersBySemester as $semester => $semesterTeachers): ?>
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Semester <?php echo $semester; ?></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 semester-teachers">
                    <?php foreach($semesterTeachers as $teacher): ?>
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300 ease-in-out overflow-hidden teacher-card">
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white"><?php echo $teacher['name']; ?></h3>
                                <p class="text-gray-600 dark:text-gray-300"><?php echo $teacher['subjects']; ?></p>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="ml-1 text-gray-600 dark:text-gray-300">
                                        <?php echo number_format(rand(35, 50)/10, 1); ?> (<?php echo rand(50, 200); ?> reviews)
                                    </span>
                                </div>
                                <a href="feedback.php?teacher=<?php echo $teacher['id']; ?>&semester=<?php echo $semester; ?>" 
                                   class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition duration-300 ease-in-out">
                                    Provide Feedback
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="mt-8 flex justify-between">
                <a href="semester.php" class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Back to Year Selection</a>
                <a href="index.php" class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 font-medium">Back to Home</a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('teacherSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const teacherCards = document.querySelectorAll('.teacher-card');
            
            teacherCards.forEach(card => {
                const teacherName = card.querySelector('h3').textContent.toLowerCase();
                const teacherSubjects = card.querySelector('p').textContent.toLowerCase();
                
                if(teacherName.includes(searchTerm) || teacherSubjects.includes(searchTerm)) {
                    card.style.display = 'block';
                    card.classList.add('animate__animated', 'animate__fadeIn');
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
