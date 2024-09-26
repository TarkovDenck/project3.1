<?php
include 'Connect.php';


function response($message, $data = [], $status = 'fail')
{
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $repassword = $_POST['repassword'] ?? '';
    $agreeTerms = isset($_POST['agreeTerms']); 


    if (empty($email) || empty($username) || empty($password) || empty($repassword)) {
        response('All fields are required!');
    }

  
    if (strlen($username) > 20) {
        response('Username cannot be more than 20 characters.');
    }

    
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        response('Password must contain at least one letter and one number, and no symbols.');
    }

 
    if (!$agreeTerms) {
        response('You must agree to the terms and conditions.');
    }

    if ($password !== $repassword) {
        response('Passwords do not match!');
    }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        response('Email or Username already exists!');
    } else {
        // Insert new user data into the database
        $stmt = $conn->prepare("INSERT INTO users (email, username, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param('sss', $email, $username, $hashed_password);

        if ($stmt->execute()) {
            response(
                message: 'You have successfully signed up.',
                data: ['email' => $email, 'username' => $username],
                status: 'success'
            );
        } else {
            response('Registration failed! Please try again.');
        }
    }
    $stmt->close();
}

$conn->close();
?>
