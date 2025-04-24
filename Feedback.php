<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/db.php';

$auth = new Auth();
if(!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$teacher_id = isset($_GET['teacher']) ? intval($_GET['teacher']) : 0;
$semester = isset($_GET['semester']) ? intval($_GET['semester']) : 1;

$database = new Database();
$db = $database->getConnection();

// Get teacher info (REMOVED t.initials)
$stmt = $db->prepare("
    SELECT t.id, t.name, GROUP_CONCAT(s.name SEPARATOR ', ') as subjects
    FROM teachers t
    JOIN teacher_subjects ts ON t.id = ts.teacher_id
    JOIN subjects s ON ts.subject_id = s.id
    WHERE t.id = ? AND s.semester = ?
    GROUP BY t.id
");
$stmt->execute([$teacher_id, $semester]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$teacher) {
    header("Location: teachers.php");
    exit();
}

// Check if feedback already submitted
$stmt = $db->prepare("SELECT id FROM feedback WHERE student_id = ? AND teacher_id = ? AND semester = ?");
$stmt->execute([$_SESSION['user_id'], $teacher_id, $semester]);
if($stmt->rowCount() > 0) {
    header("Location: thank-you.php");
    exit();
}

// Process form submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $feedback = [
        'student_id' => $_SESSION['user_id'],
        'teacher_id' => $teacher_id,
        'semester' => $semester,
        'q1' => intval($_POST['q1']),
        'q2' => intval($_POST['q2']),
        'q3' => intval($_POST['q3']),
        'q4' => intval($_POST['q4']),
        'q5' => intval($_POST['q5']),
        'q6' => intval($_POST['q6']),
        'q7' => intval($_POST['q7']),
        'q8' => intval($_POST['q8']),
        'q9' => intval($_POST['q9']),
        'q10' => intval($_POST['q10']),
        'comments' => $_POST['comments'] ?? ''
    ];

    $stmt = $db->prepare("
        INSERT INTO feedback 
        (student_id, teacher_id, semester, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, comments)
        VALUES 
        (:student_id, :teacher_id, :semester, :q1, :q2, :q3, :q4, :q5, :q6, :q7, :q8, :q9, :q10, :comments)
    ");
    
    if($stmt->execute($feedback)) {
        header("Location: thank-you.php");
        exit();
    } else {
        $error = "Failed to submit feedback. Please try again.";
    }
}

// Questions
$questions = [
    "The teacher demonstrates thorough knowledge of the subject matter.",
    "The teacher presents the material in an organized and clear manner.",
    "The teacher encourages student participation and questions.",
    "The teacher provides constructive feedback on assignments and exams.",
    "The teacher is available for help outside of class.",
    "The teacher uses teaching methods that facilitate learning.",
    "The teacher treats students with respect.",
    "The teacher presents material at an appropriate pace.",
    "The teacher relates course material to real-world applications.",
    "Overall, I would rate this teacher as excellent."
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form | Student Feedback System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="font-poppins bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900">

    <!-- Navigation -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Feedback Form -->
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-700 p-8 rounded-xl shadow-xl animate__animated animate__fadeIn">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white transition-all duration-500 ease-in-out transform hover:scale-105">Teacher Feedback Form</h2>
                <p class="text-gray-600 dark:text-gray-300 mt-2">Your feedback helps improve teaching quality</p>
            </div>
            
            <!-- Teacher Info -->
            <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg mb-8 flex items-center transform hover:scale-105 transition-all duration-500">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white"><?php echo $teacher['name']; ?></h3>
                    <p class="text-gray-600 dark:text-gray-300"><?php echo $teacher['subjects']; ?></p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Semester <?php echo $semester; ?></p>
                </div>
            </div>
            
            <!-- Feedback Questions -->
            <form class="space-y-8" id="feedbackForm" method="POST">
                <?php foreach($questions as $index => $question): ?>
                <div class="border-b border-gray-200 dark:border-gray-600 pb-6 question-group">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 transition-transform duration-300 ease-in-out hover:scale-105"><?php echo ($index + 1) . '. ' . $question; ?></h3>
                    <div class="flex flex-wrap items-center gap-4 md:gap-8">
                        <?php foreach([5, 4, 3, 2, 1] as $value): 
                            $labels = [
                                5 => 'Strongly Agree',
                                4 => 'Agree',
                                3 => 'Neutral',
                                2 => 'Disagree',
                                1 => 'Strongly Disagree'
                            ];
                        ?>
                        <div class="flex items-center">
                            <input id="q<?php echo $index+1; ?>-<?php echo $value; ?>" 
                                   name="q<?php echo $index+1; ?>" 
                                   type="radio" 
                                   value="<?php echo $value; ?>" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:checked:bg-blue-600 transition-all duration-300 ease-in-out transform hover:scale-110" 
                                   required>
                            <label for="q<?php echo $index+1; ?>-<?php echo $value; ?>" 
                                   class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                <?php echo $labels[$value]; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <!-- Additional Comments -->
                <div>
                    <label for="comments" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Additional Comments</label>
                    <textarea id="comments" name="comments" rows="4" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300 dark:bg-gray-800 dark:text-white transform hover:scale-105"
                        placeholder="Any additional feedback or suggestions for improvement..."></textarea>
                </div>
                
                <!-- Submit Button -->
                <div class="mt-8 hidden" id="submitButtonContainer">
                    <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300 transform hover:scale-105">
                        Submit Feedback
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center">
                <a href="teachers.php?year=<?php echo $semester <= 2 ? 'fy' : ($semester <= 4 ? 'sy' : 'ty'); ?>" 
                   class="text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                    Back to Teacher Selection
                </a>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('feedbackForm');
            const radioButtons = form.querySelectorAll('input[type="radio"]');
            const submitButtonContainer = document.getElementById('submitButtonContainer');
            
            function checkFormCompletion() {
                const questionGroups = form.querySelectorAll('.question-group');
                let allAnswered = true;
                
                questionGroups.forEach(group => {
                    const name = group.querySelector('input[type="radio"]').name;
                    if(!form.querySelector(`input[name="${name}"]:checked`)) {
                        allAnswered = false;
                    }
                });
                
                if(allAnswered) {
                    submitButtonContainer.classList.remove('hidden');
                    submitButtonContainer.classList.add('animate__animated', 'animate__fadeInUp');
                } else {
                    submitButtonContainer.classList.add('hidden');
                }
            }
            
            radioButtons.forEach(radio => {
                radio.addEventListener('change', checkFormCompletion);
            });
            
            checkFormCompletion();
        });
    </script>
</body>
</html>
