<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('signupForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var formData = new FormData(this);

            fetch('Php/signup.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.status === 'success') {
                        Swal.fire({
                            title: 'Sign Up Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'Great'
                        }).then(() => {
                            console.log(data); // check success response
                            window.sharedData.signupData = {
                                email: data.data.email,
                                username: data.data.email,
                                password: data.data.password
                            };
                            window.location.href = 'login.html';
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                    }
                })
                .catch(error => {
                    console.error(error); // check error response
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong! \n' + error,
                        icon: 'error',
                        confirmButtonText: 'Try Again'
                    });
                });
        });
    });
</script>