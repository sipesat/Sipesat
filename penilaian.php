<?php
session_start();
include('koneksi.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['npm'])) {
    header("Location: index.php");
    exit;
}

$npm = $_SESSION['npm'];

// Query untuk menampilkan seminar dengan status "Selesai" dan nilai_seminar kosong
$query = "
    SELECT ps.id, ps.jenis_seminar, ps.tanggal_seminar, ps.status, d.nama AS dosen_pembimbing
    FROM pendaftaran_seminar ps
    JOIN mahasiswa m ON ps.mahasiswa_id = m.id
    JOIN dosen d ON ps.pembimbing_id = d.id
    WHERE m.npm = ? AND ps.status = 'Selesai' AND (ps.nilai_seminar IS NULL OR ps.nilai_seminar = 'Belum Dinilai')
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
    <title>Penilaian Seminar</title>
    <link rel="stylesheet" href="style/penilaian.css">
</head>
<body>
    <header>
        <h1>Penilaian Seminar</h1>
    </header>
    <main>
        <section class="content">
            <div class="actions">
                <a href="main.php" class="btn-back">Back to Home</a>
            </div>

            <!-- Tambahkan kontainer scrollable untuk tabel -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Jenis Seminar</th>
                            <th>Tanggal Seminar</th>
                            <th>Dosen Pembimbing</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['jenis_seminar']) ?></td>
                                    <td><?= htmlspecialchars($row['tanggal_seminar']) ?></td>
                                    <td><?= htmlspecialchars($row['dosen_pembimbing']) ?></td>
                                    <td><?= htmlspecialchars($row['status']) ?></td>
                                    <td>
                                        <a href="input_nilai.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn-input-nilai">Input Nilai</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">Tidak ada seminar yang perlu dinilai.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 SIPESAT. All rights reserved.</p>
    </footer>
</body>
</html>
