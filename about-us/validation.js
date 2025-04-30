document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('contact-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Stop the form from submitting for now

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = document.getElementById('message').value.trim();

        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

        let nameError = document.getElementById("nameError");
        let emailError = document.getElementById("emailError");
        let messageError = document.getElementById("messageError");

        let isValid = true;

        // Validation
        if (name === '') {
            nameError.innerHTML = '<i class="ri-error-warning-fill"></i> Name is required.';
            isValid = false;
        } else {
            nameError.innerHTML = ''; // Clear error if valid
        }

        if (email === '') {
            emailError.innerHTML = '<i class="ri-error-warning-fill"></i> Email is required.';
            isValid = false;
        } else if (!emailPattern.test(email)) {
            emailError.innerHTML = '<i class="ri-error-warning-fill"></i> Please enter a valid email address.';
            isValid = false;
        } else {
            emailError.innerHTML = ''; // Clear error if valid
        }

        if (message === '') {
            messageError.innerHTML = '<i class="ri-error-warning-fill"></i> Message is required.';
            isValid = false;
        } else if (message.length < 4) {
            messageError.innerHTML = '<i class="ri-error-warning-fill"></i> Message must be at least 4 characters long.';
            isValid = false;
        } else {
            messageError.innerHTML = ''; // Clear error if valid
        }

        // If all is good, show a confirmation and reset
        if (isValid) {
            alert('Message sent! Thank you for the response.');
            this.reset(); // clear the form
        }
    });
});