<!DOCTYPE html>
<html lang="en">
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <!-- Google Font: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- REMIXICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../style/styles.css">
    <link rel="stylesheet" href="../style/login-signUpStyle.css">
</head>
<body>
    <?php include '../includes/header.php';?>

    <section class="signUp-section">
        <h1 class="authentication-heading">Create Account</h1>

        <div class="form-container">
            <div class="form-field">
                <p>First Name</p>
                <input type="FName" placeholder="Enter your first name">
            </div>

            <div class="form-field">
                <p>Last Name</p>
                <input type="LName" placeholder="Enter your last name">
            </div>

            <div class="form-field">
                <p>Email Address</p>
                <input type="email" placeholder="Enter your email">
            </div>

            <div class="form-field">
                <p>Password</p>
                <input type="password" placeholder="Enter your password">
            </div>
            <button>Sign Up</button>
        </div>
    </section>

    <?php include '../includes/footer.php';?>
</body>
</html>