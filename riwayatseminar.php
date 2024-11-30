<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['npm'])) {
    header("Location: index.php");
    exit;
}

$npm = $_SESSION['npm'];

// Ambil data riwayat seminar dengan nilai_seminar dan nilai_mutu sudah terisi
$query = "
    SELECT ps.id, ps.jenis_seminar, ps.tanggal_seminar, ps.status, ps.nilai_seminar, ps.nilai_mutu, d.nama AS nama_dosen
    FROM pendaftaran_seminar ps
    JOIN dosen d ON ps.pembimbing_id = d.id
    JOIN mahasiswa m ON ps.mahasiswa_id = m.id
    WHERE m.npm = ? AND ps.status = 'Selesai' 
    AND ps.nilai_seminar IS NOT NULL AND ps.nilai_mutu IS NOT NULL
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $npm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Seminar</title>
    <link rel="stylesheet" href="style/styleriwayat.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Riwayat Seminar</h1>
    <table>
        <thead>
            <tr>
                <th>Jenis Seminar</th>
                <th>Tanggal Seminar</th>
                <th>Status</th>
                <th>Dosen Pembimbing</th>
                <th>Nilai Seminar</th>
                <th>Nilai Mutu</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['jenis_seminar']); ?></td>
                <td><?= htmlspecialchars($row['tanggal_seminar'] ?? 'Belum Dijadwalkan'); ?></td>
                <td><?= htmlspecialchars($row['status']); ?></td>
                <td><?= htmlspecialchars($row['nama_dosen']); ?></td>
                <td><?= htmlspecialchars($row['nilai_seminar']); ?></td>
                <td><?= htmlspecialchars($row['nilai_mutu']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
