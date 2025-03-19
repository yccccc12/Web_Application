<?php
    session_start();

    require_once '../classes/user.php';

    $emailError = $passwordError = $errorMessage = "";
    $email = $password = ""; // Initialize variables

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = new User();

        // Ensure form fields exist before accessing them
        $email = isset($_POST['email']) ? trim($_POST['email']) : "";
        $password = isset($_POST['password']) ? trim($_POST['password']) : "";
        $valid = true;

        // Backend Validation
        if (empty($email)) {
            $emailError = '<i class="ri-error-warning-fill"></i> Email is required.';
            $valid = false;
        } elseif(!$user->isEmailExists($email)){
            $emailError = '<i class="ri-error-warning-fill"></i> Email not registered.';
            $valid = false;
        }

        if (empty($password)) {
            $passwordError = '<i class="ri-error-warning-fill"></i> Password is required.';
            $valid = false;
        }

        // If valid, try to log the user in
        if ($valid) {
            if ($user->login($email, $password)) {
                $_SESSION['user_email'] = $email;
                echo "<script>
                        alert('Login successfully! Redirecting to dashboard...');
                        window.location.href = '/Web_Application';
                      </script>";
                exit();
            } else {
                $passwordError = '<i class="ri-error-warning-fill"></i> Invalid password.';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Google Font: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- REMIXICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../style/login-signUpStyle.css">
    
    <!-- JavaScript -->
    <script src="../user/validation.js"></script>
</head>
<body>
    <?php include '../includes/header.php';?>

    <section class="login-section">
        <h1 class="authentication-heading">Login</h1>

        <form id="login-form" class="form-container" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-field">
                <p>Email Address</p>
                <input type="text" id="email" name="email" placeholder="Enter your email" oninput="validateEmail()" 
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                <div id="emailError" class="error"><?php echo $emailError; ?></div>
            </div>

            <div class="form-field">
                <p>Password</p>
                <div class="password-input-container">
                    <input type="password" id="password" name="password" placeholder="Enter your password"  oninput="validatePassword()">
                    <button type="button" id="togglePassword">
                        <i class="ri-eye-off-line"></i>
                    </button>
                </div>
                <div id="passwordError" class="error"><?php echo $passwordError; ?></div>
            </div>

            <button type="submit">Log In</button>

            <!-- Create Account Link -->
            <p class="create-account">Don't have an account? <a href="../user/signUp.php">Create Account</a></p>
        </form>
    </section> 
    
    <?php include '../includes/footer.php';?>
</body>
</html>