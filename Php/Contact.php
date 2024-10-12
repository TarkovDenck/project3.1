<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'Connect.php';

// Ambil data dari permintaan POST
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';

// Log untuk debugging
error_log("Email: " . $email);
error_log("Message: " . $message);

// Validasi input yang diperlukan
if (empty($email) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields.']);
    exit;
}

// Log setelah validasi
error_log("Input valid. Email: " . $email . ", Message: " . $message);

// Langkah 1: Cek apakah email sudah terdaftar di tabel `users`
$userCheckStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
if ($userCheckStmt === false) {
    error_log("Failed to prepare user check statement.");
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare user check statement.']);
    exit;
}

$userCheckStmt->bind_param('s', $email);
$userCheckStmt->execute();
$userCheckStmt->store_result();

if ($userCheckStmt->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'This email is not registered.']);
    $userCheckStmt->close();
    exit;
}
$userCheckStmt->close();

// Cek apakah email yang sama sudah pernah memberikan pesan yang sama
$duplicateCheckStmt = $conn->prepare("SELECT id FROM contacts WHERE email = ? AND message = ?");
if ($duplicateCheckStmt === false) {
    error_log("Failed to prepare duplicate check statement.");
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare duplicate check statement.']);
    exit;
}

$duplicateCheckStmt->bind_param('ss', $email, $message);
$duplicateCheckStmt->execute();
$duplicateCheckStmt->store_result();

if ($duplicateCheckStmt->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'You have already sent this message.']);
    $duplicateCheckStmt->close();
    exit;
}
$duplicateCheckStmt->close();

// Jika belum ada pesan yang sama, simpan ke tabel `contacts`
$contactStmt = $conn->prepare("INSERT INTO contacts (email, message) VALUES (?, ?)");
if ($contactStmt === false) {
    error_log("Failed to prepare contact statement.");
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare contact statement.']);
    exit;
}

$contactStmt->bind_param('ss', $email, $message);

if ($contactStmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully!']);
} else {
    error_log("Failed to execute statement for email: " . $email);
    echo json_encode(['status' => 'error', 'message' => 'Failed to send your message. Please try again later.']);
}

// Tutup semua pernyataan dan koneksi
$contactStmt->close();
$conn->close();
?>
