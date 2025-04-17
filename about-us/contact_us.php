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
                    <p><i class="ri-map-pin-line"></i> 123 Jalan Utama, Bandar Cityville, 43500 Semenyih</p>
                    <p><i class="ri-mail-line"></i> tub_apparel@gmail.com</p>
                    <p><i class="ri-phone-line"></i> +603-1234-5678</p>
                </div>
                <form id="contact-form" method="POST">
                    <div class="form-field">
                        <input type="text" id="name" name="name" placeholder="Name" required>
                    </div>
                    <div class="form-field">
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="form-field">
                        <textarea id="message" name="message" placeholder="Message" required></textarea>
                    </div>
                    <button id="contact-button">Send</button>
                </form>
            </div>
        </div>

        <?php include '../includes/footer.php'; ?>
        <script>
            document.getElementById('contact-form').addEventListener('submit', function(event) {
                event.preventDefault(); // prevent actual form submission
                alert('Message sent! Thank you for the response'); // show alert
                this.reset(); // clear form fields
            });
        </script>
    </body>
</html>