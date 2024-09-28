<?php
// Include file koneksi database
include 'Connect.php'; // Pastikan path file `Connect.php` sudah benar

// Fungsi untuk merespons JSON
function response($message, $data = [], $status = 'fail')
{
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit;
}

// Pastikan ini hanya dieksekusi untuk request POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form yang dikirim
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $repassword = $_POST['repassword'] ?? '';
    $agreeTerms = isset($_POST['agreeTerms']); // Cek apakah checkbox dicentang

    // Validasi input fields
    if (empty($email) || empty($username) || empty($password) || empty($repassword)) {
        response('All fields are required!');
    }

    // Validasi panjang username
    if (strlen($username) > 20) {
        response('Username cannot be more than 20 characters.');
    }

    // Validasi kekuatan password (minimal 8 karakter, ada huruf dan angka, tanpa simbol)
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        response('Password must contain at least one letter and one number, and no symbols.');
    }

    // Validasi persetujuan syarat dan ketentuan
    if (!$agreeTerms) {
        response('You must agree to the terms and conditions.');
    }

    // Validasi password yang cocok
    if ($password !== $repassword) {
        response('Passwords do not match!');
    }

    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah email atau username sudah ada di database
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ? OR username = ?");
    if (!$stmt) {
        response('Query preparation failed: ' . $conn->error);
    }
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        response('Email or Username already exists!');
    } else {
        // Masukkan data pengguna baru ke database
        $stmt = $conn->prepare("INSERT INTO users (email, username, password, created_at) VALUES (?, ?, ?, NOW())");
        if (!$stmt) {
            response('Query preparation failed: ' . $conn->error);
        }
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

// Tutup koneksi
$conn->close();
?>
