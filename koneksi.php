<?php
// Konfigurasi database
$host = "localhost";  // Biasanya "localhost" jika database di server yang sama
$username = "root";  // Username MySQL Anda
$password = "";  // Password MySQL Anda
$database = "db_pendaftaran";  // Nama database Anda

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set karakter encoding ke UTF-8 (opsional, tapi disarankan)
$conn->set_charset("utf8mb4");

// Catatan: Tidak perlu menutup koneksi di sini karena file ini akan di-include
// di file lain. Koneksi akan ditutup di file yang meng-include.
?>