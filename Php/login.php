<?php
session_start(); // Start session for session management

include 'Connect.php'; // Include your database connection

function response($message, $data = [], $status = 'fail') {
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input
    if (empty($username) || empty($password)) {
        response('Both username/email and password are required!');
    }

    // Persiapkan statement untuk mengambil user dari database
    $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param('ss', $username, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $fetched_username, $fetched_email, $hashed_password);
        $stmt->fetch();

        // Verifikasi password menggunakan password_verify()
        if (password_verify($password, $hashed_password)) {
            // Set session variables upon successful login
            $_SESSION['username'] = $fetched_username; // Simpan nama pengguna dalam sesi
            $_SESSION['user_id'] = $id; // Simpan ID pengguna dalam sesi
            $_SESSION['logged_in'] = true; // Menandakan bahwa pengguna telah login

            response('Login successful!', ['id' => $id, 'username' => $fetched_username, 'email' => $fetched_email], 'success');
        } else {
            response('Incorrect password.');
        }
    } else {
        response('User not found.');
    }
    $stmt->close();
}

$conn->close();
?>
