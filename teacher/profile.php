<?php
require_once '../includes/db.php';
require_once '../includes/auth_teacher.php';

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

$pdo = (new Database())->getConnection();
$teacher_id = $_SESSION['teacher_id'];

// 1. Fetch teacher + subject_name via JOIN
$stmt = $pdo->prepare("
    SELECT 
      t.*, 
      s.name AS subject_name 
    FROM teachers t
    LEFT JOIN teacher_subjects ts ON ts.teacher_id = t.id
    LEFT JOIN subjects s ON s.id = ts.subject_id
    WHERE t.id = ?
");
$stmt->execute([$teacher_id]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

// 2. Handle form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $department = $_POST['department'];
    $semester   = $_POST['semester'];
    $subject    = trim($_POST['subject']);

    // Removed image upload code. Using existing profile image value.
    $profile_image = $teacher['profile_image'];

    // 3. Update teachers table
    $upd = $pdo->prepare("
      UPDATE teachers 
      SET name = ?, email = ?, department = ?, semester = ?, profile_image = ?
      WHERE id = ?
    ");
    $upd->execute([$name, $email, $department, $semester, $profile_image, $teacher_id]);

    // 4. Ensure subject exists
    $subChk = $pdo->prepare("SELECT id FROM subjects WHERE name = ? AND semester = ?");
    $subChk->execute([$subject, $semester]);
    $subId = $subChk->fetchColumn();
    if (!$subId) {
        $insSub = $pdo->prepare("INSERT INTO subjects (name, semester) VALUES (?, ?)");
        $insSub->execute([$subject, $semester]);
        $subId = $pdo->lastInsertId();
    }

    // 5. Update or Insert teacher_subjects mapping
    $mapChk = $pdo->prepare("SELECT COUNT(*) FROM teacher_subjects WHERE teacher_id = ?");
    $mapChk->execute([$teacher_id]);
    if ($mapChk->fetchColumn() > 0) {
        $updMap = $pdo->prepare("UPDATE teacher_subjects SET subject_id = ? WHERE teacher_id = ?");
        $updMap->execute([$subId, $teacher_id]);
    } else {
        $insMap = $pdo->prepare("INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES (?, ?)");
        $insMap->execute([$teacher_id, $subId]);
    }

    header("Location: success.php?msg=" . urlencode("Profile updated successfully!"));
    exit();
}

// 6. Compute avg feedback
$avg = $pdo->prepare("SELECT ROUND(AVG((q1+q2+q3+q4+q5+q6+q7+q8+q9+q10)/10),2) FROM feedback WHERE teacher_id = ?");
$avg->execute([$teacher_id]);
$avg_score = $avg->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
      @keyframes fadeIn { from {opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
      .animate__fadeIn { animation:fadeIn .7s ease-out; }
      .animate__delay-1s { animation-delay:1s; }
      .animate__delay-2s { animation-delay:2s; }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-300 via-purple-300 to-pink-300 min-h-screen flex items-center justify-center font-roboto">
  <div class="max-w-4xl w-full mx-auto p-8 bg-white rounded-xl shadow-xl animate__animated animate__fadeIn">
    <h1 class="text-3xl font-bold mb-6 text-center text-purple-600">👩‍🏫 My Profile</h1>
    <div class="flex flex-col md:flex-row gap-8">
      <!-- Optionally, remove or adjust the profile image display -->
      <div class="w-full md:w-1/3 flex flex-col items-center">
        <?php 
          // If you need to keep a default image display, set a static image source
          $img = "https://cdn-icons-png.flaticon.com/512/9706/9706583.png";
        ?>
        <img src="<?= $img ?>" alt="Profile" class="w-40 h-40 rounded-full object-cover shadow-md border mb-4">
      </div>

      <!-- Profile Form -->
      <form method="post" class="w-full md:w-2/3 space-y-6">
        <!-- Name, Email, Dept -->
        <?php foreach (['name'=>'Full Name','email'=>'Email','department'=>'Department'] as $field=>$label): ?>
        <div>
          <label class="block text-lg font-medium text-gray-700"><?= $label ?></label>
          <input 
            type="<?= $field==='email'?'email':'text' ?>" 
            name="<?= $field ?>" 
            value="<?= htmlspecialchars($teacher[$field]) ?>" 
            required 
            class="w-full border p-3 rounded-lg"
          />
        </div>
        <?php endforeach; ?>

        <!-- Semester -->
        <div>
          <label class="block text-lg font-medium text-gray-700">Semester</label>
          <select name="semester" required class="w-full border p-3 rounded-lg">
            <option value="" disabled>Select semester</option>
            <?php for($i=1;$i<=6;$i++): ?>
              <option value="<?= $i ?>" <?= $teacher['semester']==$i?'selected':'' ?>>Semester <?= $i ?></option>
            <?php endfor; ?>
          </select>
        </div>

        <!-- Subject (text input) -->
        <div>
          <label class="block text-lg font-medium text-gray-700">Subject</label>
          <input 
            type="text" 
            name="subject" 
            value="<?= htmlspecialchars($teacher['subject_name']) ?>" 
            placeholder="Enter subject name" 
            required 
            class="w-full border p-3 rounded-lg"
          />
        </div>

        <!-- Image Upload section removed -->

        <button type="submit" class="w-full bg-purple-600 text-white p-3 rounded-lg hover:bg-purple-700 transition transform hover:scale-105">Update Profile</button>
      </form>
    </div>

    <div class="mt-8 text-center">
      <p class="text-lg text-gray-700">Average Feedback: <strong class="text-green-600"><?= $avg_score ?>/10</strong></p>
    </div>
  </div>
</body>
</html>
