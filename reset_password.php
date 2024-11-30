<?php
include 'koneksi.php'; // Include koneksi database

// Variabel untuk pesan error atau sukses
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['password'];
    $reset_token = $_POST['token'];

    // Validasi token
    $stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE reset_token = ?");
    $stmt->bind_param("s", $reset_token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token valid, update password
        $user = $result->fetch_assoc();
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update password
        $stmt = $conn->prepare("UPDATE mahasiswa SET password = ?, reset_token = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $hashed_password, $reset_token);
        $stmt->execute();

        $message = "Password Anda telah berhasil diperbarui. Silakan login.";
    } else {
        // Token tidak valid
        $message = "Token reset tidak valid atau sudah kadaluarsa.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <form action="reset_password.php" method="POST">
        <label for="password">Password Baru:</label>
        <input type="password" name="password" required>
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <button type="submit">Reset Password</button>
    </form>
    <?php if ($message) echo "<p>$message</p>"; ?>
</body>
</html>
