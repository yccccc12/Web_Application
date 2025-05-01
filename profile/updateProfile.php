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

$user = new User();
$success = $user->updateUserInfo($user_id, $name, $phone, $email, $birthday, $gender);

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