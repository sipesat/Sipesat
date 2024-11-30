<?php
include 'koneksi.php'; // Include koneksi database

// Variabel untuk pesan error atau sukses
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Cek apakah email terdaftar di database
    $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email ditemukan, lanjutkan ke halaman reset password
        $user = $result->fetch_assoc();
        $reset_token = bin2hex(random_bytes(16)); // Token acak untuk reset

        // Simpan token ke database (bisa disimpan dalam tabel reset_tokens jika ingin)
        $stmt = $conn->prepare("UPDATE mahasiswa SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $reset_token, $email);
        $stmt->execute();

        // Buat link reset password
        $reset_link = "http://localhost/reset_password.php?token=$reset_token";

        // Kirim email dengan link reset password
        mail($email, "Reset Password", "Klik link berikut untuk mereset password Anda: $reset_link");

        $message = "Kami telah mengirimkan email untuk mereset password Anda.";
    } else {
        // Jika email tidak terdaftar
        $message = "Email tidak terdaftar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <form action="forgot_password.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Submit</button>
    </form>
    <?php if ($message) echo "<p>$message</p>"; ?>
</body>
</html>
