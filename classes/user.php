<?php
require_once __DIR__ . '/database.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
        
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function register($name, $phone, $email, $password) {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare statement
        $stmt = $this->conn->prepare("INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $email, $hashedPassword);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function login($email, $password) {
        // Prepare statement
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE BINARY email = ?"); // BINARY means case sensitive
        $stmt->bind_param("s", $email);
        
        // Execute the statement and fetch the result
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($storedPassword);
        
        // Check if a result was returned (email exists)
        if ($stmt->num_rows > 0) {
            $stmt->fetch(); // Fetch the password hash
            
            // Verify the password
            if (password_verify($password, $storedPassword)) {
                return true; // Correct password
            } else {
                $stmt->close();
                return false; // Incorrect password
            }
        } else {
            $stmt->close();
            return false; // No user found
        }   
    }

    public function isEmailExists($email){
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE BINARY email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0; // Return true if email exists, false otherwise
    }

    public function __destruct() {
        Database::close();
    }
}
?>