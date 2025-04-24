<?php
require_once 'db.php';
require_once 'functions.php';

class Auth {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Student registration
    public function register($student_id, $first_name, $last_name, $email, $password) {
        $student_id = sanitizeInput($student_id);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        // Check existing user
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? OR student_id = ?");
        $stmt->execute([$email, $student_id]);
        
        if($stmt->rowCount() > 0) {
            return false;
        }

        // Create user
        $hashed_password = password_hash($password, PASSWORD_ALGO, PASSWORD_OPTIONS);
        $stmt = $this->db->prepare("INSERT INTO users (student_id, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $student_id,
            sanitizeInput($first_name),
            sanitizeInput($last_name),
            $email,
            $hashed_password
        ]);
    }

    // Student login
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([filter_var($email, FILTER_SANITIZE_EMAIL)]);
        
        if($user = $stmt->fetch()) {
            if(password_verify($password, $user['password'])) {
                $this->startUserSession($user);
                return true;
            }
        }
        return false;
    }

    // Admin login
    public function adminLogin($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([sanitizeInput($username)]);
        
        if($admin = $stmt->fetch()) {
            if(password_verify($password, $admin['password'])) {
                $this->startAdminSession($admin);
                return true;
            }
        }
        return false;
    }

    private function startUserSession($user) {
        session_regenerate_id(true);
        $_SESSION = [
            'user_id' => $user['id'],
            'user_type' => 'student',
            'student_id' => $user['student_id'],
            'email' => $user['email'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'logged_in' => true
        ];
    }

    private function startAdminSession($admin) {
        session_regenerate_id(true);
        $_SESSION = [
            'admin_id' => $admin['id'],
            'user_type' => 'admin',
            'admin_username' => $admin['username'],
            'logged_in' => true
        ];
    }

    public function isLoggedIn() {
        return isset($_SESSION['logged_in']);
    }

    public function isAdmin() {
        return ($_SESSION['user_type'] ?? '') === 'admin';
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        setcookie(session_name(), '', time()-3600, '/');
    }
}