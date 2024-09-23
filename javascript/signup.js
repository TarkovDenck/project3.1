document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('signupForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var email = document.getElementById('email').value.trim();
        var username = document.getElementById('username').value.trim();
        var password = document.getElementById('password').value.trim();
        var repassword = document.getElementById('repassword').value.trim();
        var agreeTerms = document.getElementById('agreeTerms').checked;

        // Regex untuk memeriksa keberadaan simbol '@' dalam email
        var emailRegex = /\S+@\S+\.\S+/;

        if (!emailRegex.test(email)) {
            Swal.fire({
                title: 'Error!',
                text: 'Please enter a valid email address.',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        } else if (username.length > 20) {
            Swal.fire({
                title: 'Error!',
                text: 'Username cannot be more than 20 characters.',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        } else if (!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/.test(password)) {
            Swal.fire({
                title: 'Error!',
                text: 'Password must contain at least one letter and one number, and no symbols.',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        } else if (password !== repassword) {
            Swal.fire({
                title: 'Error!',
                text: 'Passwords do not match.',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        } else if (!agreeTerms) {
            Swal.fire({
                title: 'Error!',
                text: 'You must agree to the terms and conditions.',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        } else {
            Swal.fire({
                title: 'Sign Up Success!',
                text: 'You have successfully signed up.',
                icon: 'success',
                confirmButtonText: 'Great'
            }).then(() => {

                window.sharedData.signupData = {
                    email: email,
                    username: username,
                    password: password
                };
                
                // For example, redirecting to another page
                window.location.href = 'login.html';
            });
        }
    });
});
