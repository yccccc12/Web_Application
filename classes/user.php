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
        $stmt = $this->conn->prepare("SELECT userID, name, email, phone, birthday, gender, password FROM users WHERE BINARY email = ?"); // Fetch additional fields
        $stmt->bind_param("s", $email);
        
        // Execute the statement and fetch the result
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $name, $email, $phone, $birthday, $gender, $storedPassword);
        
        // Check if a result was returned (email exists)
        if ($stmt->num_rows > 0) {
            $stmt->fetch(); // Fetch the user details

            // Verify the password
            if (password_verify($password, $storedPassword)) {
                // Return user details including birthday and gender
                return [
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'birthday' => $birthday,
                    'gender' => $gender
                ];
            }
        }
        $stmt->close();
        return false; // No user found or incorrect password
    }

    public function isEmailExists($email){
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE BINARY email = ?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0; // Return true if email exists, false otherwise
    }

    // New method to get user details by ID
    public function getUserById($userID) {
        $stmt = $this->conn->prepare("SELECT userID, name, email, phone, birthday, gender FROM users WHERE userID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        return $user; // Returns an associative array of user details
    }

    // New method to update user info
    public function updateUserInfo($userID, $name, $phone, $email, $birthday, $gender) {
        $stmt = $this->conn->prepare("UPDATE users SET name = ?, phone = ?, email = ?, birthday = ?, gender = ? WHERE userID = ?");
        $stmt->bind_param("sssssi", $name, $phone, $email, $birthday, $gender, $userID);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function __destruct() {
        Database::close();
    }
}
?>