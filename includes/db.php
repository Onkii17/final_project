<?php
class Database {
    private $host = "localhost";
    private $db_name = "feedback_system";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection(): PDO {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}",
                                      $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }
        return $this->conn; // ✅ This line is critical!
    }
}
?>