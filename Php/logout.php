<?php
session_start(); // Memulai sesi

// Hapus semua variabel sesi
session_unset();

// Hapus sesi
session_destroy();

// Jika `return_url` ada di parameter URL, redirect ke halaman tersebut, jika tidak, arahkan ke halaman default seperti `home.php`
$return_url = isset($_GET['return_url']) ? $_GET['return_url'] : 'home.php';

// Redirect ke URL sebelumnya atau ke halaman home
header("Location: $return_url");
exit();
?>
