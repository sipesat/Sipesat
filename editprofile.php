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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $semester = $_POST['semester'];
    $angkatan = $_POST['angkatan'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_hp = $_POST['no_hp'];

    $update_stmt = $conn->prepare("UPDATE mahasiswa SET semester = ?, angkatan = ?, jenis_kelamin = ?, no_hp = ? WHERE npm = ?");
    $update_stmt->bind_param("issss", $semester, $angkatan, $jenis_kelamin, $no_hp, $npm);

    if ($update_stmt->execute()) {
        header("Location: profile.php");
        exit;
    } else {
        echo "Terjadi kesalahan saat memperbarui profil.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="style/styleprofile.css">
</head>
<body>
    <section>
        <div class="form-box" id="profile-box">
            <div class="form-value">
                <h2>Edit Profil</h2>
                <form method="POST">
                    <div class="inputbox">
                        <label>Semester</label>
                        <input type="number" name="semester" value="<?= $user['semester'] ?>">
                    </div>
                    <div class="inputbox">
                        <label>Angkatan</label>
                        <input type="text" name="angkatan" value="<?= $user['angkatan'] ?>">
                    </div>
                    <div class="inputbox">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin">
                            <option value="L" <?= ($user['jenis_kelamin'] == 'L') ? 'selected' : '' ?>>Laki-Laki</option>
                            <option value="P" <?= ($user['jenis_kelamin'] == 'P') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="inputbox">
                        <label>No HP</label>
                        <input type="text" name="no_hp" value="<?= $user['no_hp'] ?>">
                    </div>
                    <button type="submit">Perbarui Profil</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>