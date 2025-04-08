<?php
session_start();
require_once '../classes/user.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$birthday = $_POST['birthday'];
$gender = $_POST['gender'];

// $conn = Database::connect();
$user = new User();

/*
// Check if email already exists (excluding the current user's email)
$stmt = $conn->prepare("SELECT userID FROM users WHERE email = ? AND userID != ?");
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: EditProfile.php?status=email_exists");
    exit;
}
$stmt->close();


// Update user details including email

$stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, email = ?, birthday = ?, gender = ? WHERE userID = ?");
$stmt->bind_param("sssssi", $name, $phone, $email, $birthday, $gender, $user_id);
$success = $stmt->execute();
$stmt->close();
*/

$success = $user->updateUserInfo($user_id, $name, $phone, $email, $birthday, $gender);
// Database::close();

if ($success) {
    $_SESSION['user_name'] = $name;
    $_SESSION['user_phone'] = $phone;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_birthday'] = $birthday;
    $_SESSION['user_gender'] = $gender;
    
    header("Location: personalInfo.php?status=success");
    exit;
} else {
    header("Location: personalInfo.php?status=error");
    exit;
}
?>
