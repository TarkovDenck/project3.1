document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var username = document.getElementById('username').value.trim();
        var password = document.getElementById('password').value.trim();

        console.log('Username:', username);  // Debugging
        console.log('Password:', password);  // Debugging

        // Validasi untuk email Gmail
        var gmailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

        if (username === "" || password === "") {
            Swal.fire({
                title: 'Error!',
                text: 'Username or password cannot be empty.',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        } else if (gmailRegex.test(username) || username === password) {
            Swal.fire({
                title: 'Login Success!',
                text: 'You have successfully logged in with your Gmail account.',
                icon: 'success',
                confirmButtonText: 'Great'
            }).then(() => {
                // Logic for successful login can be added here
                window.location.href = '../HomePage.html';
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Incorrect username or password.',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        }
    });
});
