<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUB Concept Store</title>

    <!-- Google Font: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- REMIXICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../StyleLogin/styleLogin.css">
</head>
<body>
    <?php include '../includes/header.php';?>

    <h1 id=login>Login</h1>

    <div class="BigContainer">

        <div class="EmailContainer">
            <h2>Email Address</h2>
            <input type="email" placeholder="Enter your email">
        </div>

        <div class="PasswordContainer">
            <h2>Password</h2>
            <input type="password" placeholder="Enter your password">
        </div>

        <button>Sign In</button>

        <!-- Create Account Link -->
        <p class="create-account">Don't have an account? <a href="../SignUp">Create Account</a></p>
    </div>
    
    
</body>
</html>