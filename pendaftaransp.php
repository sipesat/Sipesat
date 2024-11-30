<?php
// Mulai sesi atau sesuaikan dengan sistem login Anda
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
    $pembimbing_kedua_id = isset($_POST['pembimbing_kedua']) ? $_POST['pembimbing_kedua'] : null;
    $judul_penelitian = htmlspecialchars($_POST['judul_penelitian']);
    $abstrak_penelitian = htmlspecialchars($_POST['abstrak_penelitian']);
    $surat_penelitian = htmlspecialchars($_POST['surat_penelitian']);
    $krs_penelitian = htmlspecialchars($_POST['krs_penelitian']);

    // Proses pembimbing utama
    if (isset($_POST['pembimbing']) && $_POST['pembimbing'] === 'other') {
        $pembimbing_lain = htmlspecialchars($_POST['pembimbing_lain']);
        $stmt = $conn->prepare("INSERT INTO dosen (nama) VALUES (?)");
        $stmt->bind_param("s", $pembimbing_lain);
        $stmt->execute();
        $pembimbing_id = $stmt->insert_id;
        $stmt->close();
    }

    // Validasi pembimbing utama
    $stmt_check_pembimbing = $conn->prepare("SELECT id FROM dosen WHERE id = ?");
    $stmt_check_pembimbing->bind_param("i", $pembimbing_id);
    $stmt_check_pembimbing->execute();
    $result_pembimbing = $stmt_check_pembimbing->get_result();
    if ($result_pembimbing->num_rows === 0) {
        echo "Pembimbing utama tidak valid!";
        exit;
    }

    $stmt_check_pembimbing->close();

    // Proses pembimbing kedua (opsional)
    if (isset($_POST['pembimbing_kedua']) && $_POST['pembimbing_kedua'] === 'other') {
        $pembimbing_kedua_lain = htmlspecialchars($_POST['pembimbing_kedua_lain']);
        $stmt = $conn->prepare("INSERT INTO dosen (nama) VALUES (?)");
        $stmt->bind_param("s", $pembimbing_kedua_lain);
        $stmt->execute();
        $pembimbing_kedua_id = $stmt->insert_id;
        $stmt->close();
    }

    // Validasi pembimbing kedua jika ada
    if ($pembimbing_kedua_id !== null) {
        $stmt_check_pembimbing_kedua = $conn->prepare("SELECT id FROM dosen WHERE id = ?");
        $stmt_check_pembimbing_kedua->bind_param("i", $pembimbing_kedua_id);
        $stmt_check_pembimbing_kedua->execute();
        $result_pembimbing_kedua = $stmt_check_pembimbing_kedua->get_result();
        if ($result_pembimbing_kedua->num_rows === 0) {
            echo "Pembimbing kedua tidak valid!";
            exit;
        }
        $stmt_check_pembimbing_kedua->close();
    }

    $stmt = $conn->prepare("INSERT INTO pendaftaran_seminar 
        (mahasiswa_id, pembimbing_id, pembimbing_kedua_id, jenis_seminar, status, judul_penelitian, abstrak_penelitian,
        surat_penelitian, krs_penelitian) 
        VALUES (?, ?, ?, 'Penelitian', 'Menunggu', ?, ?, ?, ?)");

    if ($stmt) {
    // Gunakan variabel sementara untuk pembimbing kedua
    $pembimbing_kedua_id_final = $pembimbing_kedua_id ?: null;

    $stmt->bind_param(
        "iiissss",
        $mahasiswa_id,
        $pembimbing_id,
        $pembimbing_kedua_id_final, // Gunakan variabel di sini
        $judul_penelitian,
        $abstrak_penelitian,
        $surat_penelitian,
        $krs_penelitian
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Pendaftaran Seminar Kerja Penelitian berhasil!";
        header("Location: pendaftaransp.php?status=success");
        exit;
    } else {
        error_log("Error during seminar registration: " . $stmt->error);
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
    } else {
    echo "Gagal mempersiapkan statement: " . $conn->error;
    }

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Seminar Penelitian</title>
    <link rel="stylesheet" href="style/stylependaftaran.css">
</head>
<body>
    <header>
        <h1>Seminar Penelitian Prodi Teknik Kimia</h1>
    </header>
    
    <main>
        <h1>Pendaftaran Seminar Penelitian Prodi Teknik Kimia</h1>
        <p>Berikut form pendaftaran Seminar Penelitian Program Studi Teknik Kimia UNSIKA</p>
        <script>
            window.onload = function() {
                // Jika status dikirimkan lewat URL
                const urlParams = new URLSearchParams(window.location.search);
                const status = urlParams.get('status');

                if (status === 'success') {
                    alert("Pendaftaran Seminar Penelitian berhasil!");
                } else if (status === 'error') {
                    alert("Terjadi kesalahan. Silakan coba lagi.");
                }
            }
        </script>
        <form action="pendaftaransp.php" method="POST" class="form-seminar">
            <div class="form-section">
                <h2>Pembimbing<span class="required">*</span></h2>
                <div class="radio-group pembimbing-group">
                    <label><input type="radio" name="pembimbing" value="1" required> Dr. MEKA SAIMA PERDANI, S.Si., M.T.</label>
                    <label><input type="radio" name="pembimbing" value="2" required> MUHAMMAD FAHMI HAKIM, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing" value="3" required> AZAFILMI HAKIM, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing" value="4" required> CINTIYA SEPTA HASANNAH, S.T., M.Eng.</label>
                    <label><input type="radio" name="pembimbing" value="5" required> SARAH DAMPANG, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing" value="6" required> DESSY AGUSTINA SARI, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing" value="7" required> TEGUH PAMBUDI, S.Pd., M.Sc.</label>
                    <label><input type="radio" name="pembimbing" value="other" id="otherPembimbing" required> Pembimbing Lain</label>
                    <input type="text" id="inputPembimbingLain" name="pembimbing_lain" placeholder="Masukkan nama pembimbing lain">
                </div>
            </div>

            <div class="form-section">
                <h2>Pembimbing Kedua (Opsional Jika Ada)</h2>
                <div class="radio-group pembimbing-group">
                    <label><input type="radio" name="pembimbing_kedua" value="1"> Dr. MEKA SAIMA PERDANI, S.Si., M.T.</label>
                    <label><input type="radio" name="pembimbing_kedua" value="2"> MUHAMMAD FAHMI HAKIM, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing_kedua" value="3"> AZAFILMI HAKIM, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing_kedua" value="4"> CINTIYA SEPTA HASANNAH, S.T., M.Eng.</label>
                    <label><input type="radio" name="pembimbing_kedua" value="5"> SARAH DAMPANG, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing_kedua" value="6"> DESSY AGUSTINA SARI, S.T., M.T.</label>
                    <label><input type="radio" name="pembimbing_kedua" value="7"> TEGUH PAMBUDI, S.Pd., M.Sc.</label>
                    <label><input type="radio" name="pembimbing_kedua" value="other" id="otherPembimbingKedua"> Pembimbing Kedua Lain</label>
                    <input type="text" id="inputPembimbingKeduaLain" name="pembimbing_kedua_lain" placeholder="Masukkan nama pembimbing kedua lain">
                </div>
            </div>

            <div class="form-section">
                <h2>Judul Penelitian<span class="required">*</span></h2>
                <label for="judul_penelitian"></label>
                <input type="text" id="judul_penelitian" name="judul_penelitian" placeholder="Masukkan judul anda" required>
            </div>

            <div class="form-section">
                <h2>Dokumen Pendukung</h2>
                <label for="abstrak_penelitian">Unggah Abstrak Penelitian<span class="required">*</span></label>
                <input type="text" id="abstrak_penelitian" name="abstrak_penelitian" required>

                <label for="surat_penelitian">Surat Permohonan Seminar Penelitian<span class="required">*</span></label>
                <input type="text" id="surat_penelitian" name="surat_penelitian" required>

                <label for="krs_penelitian">Unggah KRS yang mengambil mata kuliah penelitian<span class="required">*</span></label>
                <input type="text" id="krs_penelitian" name="krs_penelitian" required>
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
