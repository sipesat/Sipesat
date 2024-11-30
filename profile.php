<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['npm'])) {
    header("Location: index.php");
    exit;
}

$npm = $_SESSION['npm'];
$stmt = $conn->prepare("SELECT * FROM mahasiswa WHERE npm = ?");
$stmt->bind_param("s", $npm);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="style/styleprofile.css">
</head>
<body>
    <section>
        <div class="form-box" id="profile-box">
            <div class="form-value">
                <h2>Profil</h2>
                
                <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama']) ?></p>
                <p><strong>NPM:</strong> <?= htmlspecialchars($user['npm']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Semester:</strong> <?= $user['semester'] ?></p>
                <p><strong>Angkatan:</strong> <?= $user['angkatan'] ?></p>
                <p><strong>Jenis Kelamin:</strong> <?= $user['jenis_kelamin'] ?></p>
                <p><strong>No HP:</strong> <?= $user['no_hp'] ?></p>

                <form method="GET" action="editprofile.php">
                    <button type="submit">Edit Profil</button>
                </form>
            </div>
        </div>
        <button class="btn-beranda" onclick="window.location.href='main.php';">Kembali ke Beranda</button>
    </section>
</body>
</html>