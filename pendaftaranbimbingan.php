<?php
session_start();
include('koneksi.php'); // Pastikan Anda sudah mengatur koneksi database

// Cek apakah mahasiswa sudah login berdasarkan NPM yang ada di sesi
if (!isset($_SESSION['npm'])) {
    header("Location: index.php");
    exit;
}

$npm = $_SESSION['npm'];  // Ambil NPM dari sesi yang sudah login

// Mengambil data mahasiswa dari database
$stmt_mahasiswa = $conn->prepare("SELECT id, nama, no_hp, angkatan FROM mahasiswa WHERE npm = ?");
$stmt_mahasiswa->bind_param("s", $npm);
$stmt_mahasiswa->execute();
$result = $stmt_mahasiswa->get_result();

if ($result->num_rows > 0) {
    $mahasiswa = $result->fetch_assoc();
    $mahasiswa_id = $mahasiswa['id'];
    $nama = $mahasiswa['nama'];
    $no_hp = $mahasiswa['no_hp'];
    $angkatan = $mahasiswa['angkatan'];
} else {
    echo "Data mahasiswa tidak ditemukan!";
    exit;
}

$stmt_mahasiswa->close();

// Mengambil data dari form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data pembimbing
    $pembimbing_id = $_POST['pembimbing'];

    // Dokumen pendukung
    $jenis_bimbingan = $_POST['jenis_bimbingan'];
    $tanggal_bimbingan = $_POST['tanggal_bimbingan'];
    $materi_bimbingan = $_POST['materi_bimbingan'];

    // Pembimbing lainnya
    if (isset($_POST['pembimbing']) && $_POST['pembimbing'] === 'other') {
        $pembimbing_lain = $_POST['pembimbing_lain'];
        $stmt = $conn->prepare("INSERT INTO dosen (nama) VALUES (?)");
        $stmt->bind_param("s", $pembimbing_lain);
        $stmt->execute();
        $pembimbing_id = $stmt->insert_id;
        $stmt->close();
    }

    // Cek validitas pembimbing_id
    $stmt_check_pembimbing = $conn->prepare("SELECT id FROM dosen WHERE id = ?");
    $stmt_check_pembimbing->bind_param("i", $pembimbing_id);
    $stmt_check_pembimbing->execute();
    $result_pembimbing = $stmt_check_pembimbing->get_result();
    if ($result_pembimbing->num_rows === 0) {
        echo "Pembimbing yang dipilih tidak valid!";
        exit;
    }

    // Menyimpan data pendaftaran seminar
    $stmt = $conn->prepare("INSERT INTO bimbingan 
    (mahasiswa_id, dosen_id, jenis_bimbingan, tanggal_bimbingan, materi_bimbingan, status) 
    VALUES (?, ?, ?, ?, ?, 'Menunggu')");

    if ($stmt) {
        $stmt->bind_param(
            "iisss", // Sesuaikan dengan tipe data dari kolom di database
            $mahasiswa_id,  // ID Mahasiswa yang diambil dari sesi
            $pembimbing_id, // ID Pembimbing yang dipilih
            $jenis_bimbingan,
            $tanggal_bimbingan,
            $materi_bimbingan
        );

        if ($stmt->execute()) {
            echo "Pendaftaran Bimbingan Online berhasil!";
        } else {
            echo "Terjadi kesalahan: " . $stmt->error;
        }
    } else {
        echo "Gagal mempersiapkan statement: " . $conn->error;
    }

    $stmt->close();
    header("Location: pendaftaranbimbingan.php?status=success");
        exit;
    }

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Bimbingan</title>
    <link rel="stylesheet" href="style/stylependaftaran.css">
</head>
<body>
    <header>
        <h1>Pendaftaran Bimbingan</h1>
    </header>
    
    <main>
        <h1>Form Pendaftaran Bimbingan Online</h1>
        <script>
            window.onload = function() {
                // Jika status dikirimkan lewat URL
                const urlParams = new URLSearchParams(window.location.search);
                const status = urlParams.get('status');

                if (status === 'success') {
                    alert("Pendaftaran Bimbingan Online berhasil!");
                } else if (status === 'error') {
                    alert("Terjadi kesalahan. Silakan coba lagi.");
                }
            }
        </script>
        <form action="pendaftaranbimbingan.php" method="POST" class="form-seminar">
            <!-- Jenis Bimbingan -->
            <div class="form-section">
                <h2>Jenis Bimbingan</h2>
                <select name="jenis_bimbingan" id="jenis_bimbingan" required>
                    <option value="" disabled selected>Pilih jenis bimbingan</option>
                    <option value="Kerja Praktik">Kerja Praktik</option>
                    <option value="Penelitian">Penelitian</option>
                    <option value="Tugas Akhir">Tugas Akhir</option>
                </select>
            </div>

            <!-- Rentang Tanggal Bimbingan -->
            <div class="form-section">
                <h2>Rentang Tanggal Bimbingan</h2>
                <label for="tanggal_bimbingan">Rentang Tanggal:</label>
                <input type="date" id="tanggal_bimbingan" name="tanggal_bimbingan" required>
            </div>

            <div class="form-section">
                <h2>Pembimbing</h2>
                <div class="radio-group pembimbing-group">
                    <label><input type="radio" name="pembimbing" value="1"> Dr. MEKA SAIMA PERDANI, S.Si., M.T.</label>
                    <label><input type="radio" name="pembimbing" value="2"> MUHAMMAD FAHMI HAKIM, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing" value="3"> AZAFILMI HAKIM, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing" value="4"> CINTIYA SEPTA HASANNAH, S.T., M.Eng.</label>
                    <label><input type="radio" name="pembimbing" value="5"> SARAH DAMPANG, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing" value="6"> DESSY AGUSTINA SARI, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing" value="7"> TEGUH PAMBUDI, S.Pd., M.Sc.</label>
                    <label><input type="radio" name="pembimbing" value="other" id="otherPembimbing"> Pembimbing Lain</label>
                    <input type="text" id="inputPembimbingLain" name="pembimbing_lain" placeholder="Masukkan nama pembimbing lain">
                </div>
            </div>


            <!-- Materi Bimbingan -->
            <div class="form-section">
                <h2>Materi Bimbingan</h2>
                <input type="text" id="materi_bimbingan" name="materi_bimbingan"  placeholder="Jelaskan materi bimbingan" required></input>
            </div>

            <input type="submit" value="Daftar">
        </form>
    </main>

    <button class="btn-beranda" onclick="window.location.href='main.php';">Kembali ke Beranda</button>

    <footer>
        <p>&copy; 2024 SIPESAT. All rights reserved.</p>
    </footer>

    <script src="js/scriptpendaftaran.js"></script>
</body>
</html>
