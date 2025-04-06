<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../user/login.php");
    exit();
}

require_once '../classes/user.php';

// Fetch user details from the database
$user = new User();
$userData = $user->getUserById($_SESSION['user_id']); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    
    <!-- Google Font: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- REMIXICONS -->
    <link rel="stylesheet" href="../style/profile.css">
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <div class="sidebar">
            <a href="#" id="personal" style="font-weight: bold;">| Personal Info</a>
            <a href="editProfile.php">Edit Profile</a>
            <a href="#">Order History</a>
            <a href="logout.php">Log out</a>
        </div>

        <div class="content">
            <div class="card">
                <h2>Personal Info</h2>
                <br>
                <hr>

                <table class="info-table">
                    <tr>
                        <td>Name</td>
                        <td><?php echo htmlspecialchars($userData['name']); ?></td>
                    </tr>
                    <tr>
                        <td>Phone. No</td>
                        <td><?php echo htmlspecialchars($userData['phone']); ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php echo htmlspecialchars($userData['email']); ?></td>
                    </tr>
                    <tr>
                        <td>Birthday</td>
                        <td><?php echo (!empty($userData['birthday']) && $userData['birthday'] !== '0000-00-00') ? htmlspecialchars($userData['birthday']) : 'Not set'; ?></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><?php echo !empty($userData['gender']) ? htmlspecialchars($userData['gender']) : 'Not set'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php';?>
</body>
</html>

