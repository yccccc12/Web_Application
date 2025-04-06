// ============ Edit Profile Validation =================
document.addEventListener("DOMContentLoaded", function () {
    let nameInput = document.getElementById("name");
    let nameError = document.getElementById("nameError");
    let phoneInput = document.getElementById("phone");
    let phoneError = document.getElementById("phoneError");
    //let emailInput = document.getElementById("email");
    //let emailError = document.getElementById("emailError");
    let editProfileForm = document.getElementById("editProfileForm");

    if (!editProfileForm) return;

    // ============ Name Validation =================
    function validateName() {
        let name = nameInput.value.trim();
        nameError.innerHTML = "";
        let namePattern = /^[A-Za-z\s]{2,}$/;
        
        if (name === "") {
            nameError.innerHTML = '<i class="ri-error-warning-fill"></i> Name is required.';
            return;
        }
        if (!namePattern.test(name)) {
            nameError.innerHTML = '<span class="invalid">❌ Invalid name. Only letters and spaces allowed.</span>';
            return;
        }
        nameError.innerHTML = '<span class="valid">✅ Valid name.</span>';
    }

    // ============ Phone Validation =================
    function validatePhone() {
        let phone = phoneInput.value.trim();
        phoneError.innerHTML = "";
        let phonePattern = /^0\d{2}-\d{7,8}$/;

        if (phone === "") {
            phoneError.innerHTML = '<i class="ri-error-warning-fill"></i> Phone number is required.';
            return;
        }
        if (!phonePattern.test(phone)) {
            phoneError.innerHTML = '<span class="invalid">❌ Invalid phone format (e.g., 012-3456789).</span>';
            return;
        }
        phoneError.innerHTML = '<span class="valid">✅ Valid phone number.</span>';
    }

    // ============ Email Validation =================
    /*
    function validateEmail() {
        let email = emailInput.value.trim();
        emailError.innerHTML = "";
        let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/;

        if (email === "") {
            emailError.innerHTML = '<i class="ri-error-warning-fill"></i> Email is required.';
            return;
        }
        if (!emailPattern.test(email)) {
            emailError.innerHTML = '<span class="invalid">❌ Invalid email format (e.g., user@example.com).</span>';
            return;
        }
        emailError.innerHTML = '<span class="valid">✅ Valid email format.</span>';
    }
    */

    // Attach real-time validation
    if (nameInput) nameInput.addEventListener("input", validateName);
    if (phoneInput) phoneInput.addEventListener("input", validatePhone);
    //if (emailInput) emailInput.addEventListener("input", validateEmail);

    // ============ Edit Profile Form Submission Validation =================
    editProfileForm.addEventListener("submit", function (event) {
        validateName();
        validatePhone();
        //validateEmail();

        console.log("Name Error:", nameError.innerHTML);
        console.log("Phone Error:", phoneError.innerHTML);
        //console.log("Email Error:", emailError.innerHTML);

        if (nameError.innerHTML.includes("❌") || phoneError.innerHTML.includes("❌") ||
        nameInput.value.trim() === "" || phoneInput.value.trim() === "") {
        event.preventDefault(); // Stop form submission
        }

        /*
        if (nameError.innerHTML.includes("❌") || phoneError.innerHTML.includes("❌") || emailError.innerHTML.includes("❌") ||
            nameInput.value.trim() === "" || phoneInput.value.trim() === "" || emailInput.value.trim() === "") {
            event.preventDefault(); 
        }
        */
        
    });
});
