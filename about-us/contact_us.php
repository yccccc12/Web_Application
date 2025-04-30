<?php
$name = $email = $message = "";
$nameError = $emailError = $messageError = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $message = trim($_POST["message"] ?? "");
    $valid = true;

    // Validate name
    if (empty($name)) {
        $nameError = '<i class="ri-error-warning-fill"></i> Name is required.';
        $valid = false;
    }

    // Validate email
    if (empty($email)) {
        $emailError = '<i class="ri-error-warning-fill"></i> Email is required.';
        $valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = '<i class="ri-error-warning-fill"></i> Invalid email format.';
        $valid = false;
    }

    // Validate message
    if (empty($message)) {
        $messageError = '<i class="ri-error-warning-fill"></i> Message is required.';
        $valid = false;
    }

    if ($valid) {
        // You can replace this with sending email or database logic
        $successMessage = "Thank you for contacting us. We'll get back to you soon.";
        $name = $email = $message = ""; // Clear fields after success
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact Us</title>

        <!-- Google Font: Figtree -->
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- REMIXICONS -->
        <link rel="stylesheet" href="../style/styles.css">
        <link rel="stylesheet" href="../style/login-signUpStyle.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.css">
        <style>
            .contact-container {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                padding: 50px;
                max-width: 1200px;
                margin: 0 auto;
                gap: 20px;
            }

            .contact-info {
                flex: 1;
                background-color: #fff;
                padding: 0 5px 20px;
            }

            .contact-info h2 {
                font-family: Figtree;
                font-size: 24px;
                font-weight: 500;
                margin-bottom: 20px;
            }

            .contact-info p {
                margin: 10px 0;
                font-size: 16px;
                text-align: left;
                color: #555;
            }

            .contact-info p i {
                margin-right: 10px;
                color: #333;
            }

            .contact-map {
                flex: 1;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .contact-form {
                flex: 1;
                background-color: #fff;
                padding: 20px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .contact-form h2 {
                font-size: 24px;
                margin-bottom: 20px;
            }

            .form-field {
                margin-bottom: 15px;
            }

            .form-field input,
            .form-field textarea {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                font-size: 14px;
            }

            .form-field textarea {
                resize: none;
                height: 100px;
            }
            
            @media(max-width: 972px){
                .contact-container{
                    display: block;
                    padding: 20px;
                }

                .contact-map{
                    margin-bottom: 20px;
                }
            }
        </style>
    </head>
    <body>
        <?php include '../includes/header.php'; ?>

        <div class="contact-container">
            <!-- Google Map -->
            <div class="contact-map">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63742.02713729366!2d101.64838704863283!3d3.127285800000007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc37635624f525%3A0x9e6342e9a2eafd9e!2sSUB%20-%20SUNWAY%20VELOCITY!5e0!3m2!1sen!2smy!4v1744300895995!5m2!1sen!2smy" 
                    width="100%" 
                    height="475" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- Contact Form -->
            <div class="contact-form">
            <div class="contact-info">
                <h2>Contact Info</h2>
                <p><i class="ri-map-pin-line"></i> Lot 1-50A, 1st Floor, Sunway Velocity Mall, 55100 Kuala Lumpur</p>
                <p><i class="ri-mail-line"></i> tub_apparel@gmail.com</p>
                <p><i class="ri-phone-line"></i> +603-1234-5678</p>
            </div>

            <?php if ($successMessage): ?>
                <div class="success-message" style="color: green; font-weight: 500; margin-bottom: 20px;">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>

            <form id="contact-form" method="POST">
                <div class="form-field">
                    <input type="text" id="name" name="name" placeholder="Name" value="<?php echo htmlspecialchars($name); ?>">
                    <div id="nameError" class="error"><?php echo $nameError; ?></div>
                </div>
                
                <div class="form-field">
                    <input type="text" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
                    <div id="emailError" class="error"><?php echo $emailError; ?></div>
                </div>
                
                <div class="form-field">
                    <textarea id="message" name="message" placeholder="Message"><?php echo htmlspecialchars($message); ?></textarea>
                    <div id="messageError" class="error"><?php echo $messageError; ?></div>
                </div>
                <button id="contact-button" type="submit">Send</button>
            </form>
        </div>
        </div>
    <script src='validation.js'></script>
    </body>
</html>