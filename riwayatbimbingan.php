<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['npm'])) {
    header("Location: index.php");
    exit;
}

$npm = $_SESSION['npm'];

// Ambil data riwayat bimbingan
$query = "
    SELECT b.jenis_bimbingan, b.tanggal_bimbingan, b.status, b.materi_bimbingan, d.nama AS nama_dosen
    FROM bimbingan b
    JOIN dosen d ON b.dosen_id = d.id
    JOIN mahasiswa m ON b.mahasiswa_id = m.id
    WHERE m.npm = ? AND b.status IN ('Selesai')
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $npm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Bimbingan</title>
    <link rel="stylesheet" href="style/styleriwayat.css">
</head>
<body>
    <h1>Riwayat Bimbingan</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Jenis Bimbingan</th>
                <th>Tanggal Bimbingan</th>
                <th>Status</th>
                <th>Topik Bimbingan</th>
                <th>Dosen Pembimbing</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['jenis_bimbingan']; ?></td>
                <td><?= $row['tanggal_bimbingan'] ?? 'Belum Dijadwalkan'; ?></td>
                <td><?= $row['status']; ?></td>
                <td><?= $row['materi_bimbingan']; ?></td>
                <td><?= $row['nama_dosen']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
