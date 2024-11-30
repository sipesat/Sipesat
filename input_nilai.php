<?php
session_start();
include('koneksi.php');

if (!isset($_SESSION['npm']) || !isset($_GET['id'])) {
    header("Location: penilaian.php");
    exit;
}

$seminar_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nilai_angka = $_POST['nilai_angka'];
    $nilai_mutu = $_POST['nilai_mutu'];
    $scan_berita_acara = $_POST['scan_berita_acara'];
    $laporan_revisi = $_POST['laporan_revisi'];

    $query = "
        UPDATE pendaftaran_seminar 
        SET nilai_seminar = ?, nilai_mutu = ?, scan_berita_acara = ?, laporan_revisi = ? 
        WHERE id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nilai_angka, $nilai_mutu, $scan_berita_acara, $laporan_revisi, $seminar_id);

    if ($stmt->execute()) {
        header("Location: penilaian.php?status=success");
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai Seminar</title>
    <link rel="stylesheet" href="style/penilaian.css">
</head>
<body>
    <header>
        <h1>Input Nilai Seminar</h1>
    </header>
    <main>
        <form action="" method="POST">
            <label for="nilai_angka">Nilai Angka (1-100):</label>
            <input type="number" id="nilai_angka" name="nilai_angka" min="1" max="100" required>

            <label for="nilai_mutu">Nilai Mutu:</label>
            <select id="nilai_mutu" name="nilai_mutu" required>
                <option value="" disabled selected>Pilih Nilai Mutu</option>
                <option value="A">A</option>
                <option value="B+">B+</option>
                <option value="B">B</option>
                <option value="C+">C+</option>
                <option value="C">C</option>
            </select>

            <label for="scan_berita_acara">Scan Berita Acara, Daftar Nilai Seminar TA, Daftar Pernyataan, Daftar Hadir (Link):</label>
            <input type="text" id="scan_berita_acara" name="scan_berita_acara" required>

            <label for="laporan_revisi">Laporan Setelah Revisi (Link):</label>
            <input type="text" id="laporan_revisi" name="laporan_revisi" required>

            <button type="submit">Submit</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 SIPESAT. All rights reserved.</p>
    </footer>
</body>
</html>
