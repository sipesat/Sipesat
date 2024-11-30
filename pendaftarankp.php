<?php

session_start();
include('koneksi.php'); // Pastikan file koneksi database sudah benar

// Cek apakah mahasiswa sudah login
if (!isset($_SESSION['npm'])) {
    header("Location: index.php");
    exit;
}

$npm = $_SESSION['npm'];

// Mengambil data mahasiswa dari database
$stmt_mahasiswa = $conn->prepare("SELECT id, nama, no_hp, angkatan FROM mahasiswa WHERE npm = ?");
$stmt_mahasiswa->bind_param("s", $npm);
$stmt_mahasiswa->execute();
$result = $stmt_mahasiswa->get_result();

if ($result->num_rows > 0) {
    $mahasiswa = $result->fetch_assoc();
    $mahasiswa_id = $mahasiswa['id'];
    $nama = htmlspecialchars($mahasiswa['nama']);
    $no_hp = htmlspecialchars($mahasiswa['no_hp']);
    $angkatan = htmlspecialchars($mahasiswa['angkatan']);
} else {
    echo "Data mahasiswa tidak ditemukan!";
    exit;
}

$stmt_mahasiswa->close();

// Proses form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil dan sanitasi input form
    $pembimbing_id = $_POST['pembimbing'];
    $pembimbing_kedua_id = isset($_POST['pembimbing_kedua']) ? $_POST['pembimbing_kedua'] : null;
    $cover_laporan_link = htmlspecialchars($_POST['cover_laporan_link']);
    $tempat_kp = htmlspecialchars($_POST['tempat_kp']);
    $surat_permohonan_link = htmlspecialchars($_POST['surat_permohonan_link']);
    $bukti_seminar_link = htmlspecialchars($_POST['bukti_seminar_link']);
    $judul_khusus = htmlspecialchars($_POST['judul_khusus']);
    $judul_umum = htmlspecialchars($_POST['judul_umum']);

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

    // Menyimpan data pendaftaran seminar
    $stmt = $conn->prepare("INSERT INTO pendaftaran_seminar 
        (mahasiswa_id, pembimbing_id, pembimbing_kedua_id, jenis_seminar, status, tempat_kp, cover_laporan_link, 
        surat_permohonan_link, bukti_seminar_link, judul_khusus, judul_umum) 
        VALUES (?, ?, ?, 'Kerja Praktik', 'Menunggu', ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
    // Gunakan variabel sementara untuk pembimbing kedua
    $pembimbing_kedua_id_final = $pembimbing_kedua_id ?: null;

    $stmt->bind_param(
        "iiissssss",
        $mahasiswa_id,
        $pembimbing_id,
        $pembimbing_kedua_id_final, // Gunakan variabel di sini
        $tempat_kp,
        $cover_laporan_link,
        $surat_permohonan_link,
        $bukti_seminar_link,
        $judul_khusus,
        $judul_umum
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Pendaftaran Seminar Kerja Praktik berhasil!";
        header("Location: pendaftarankp.php?status=success");
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


<!-- HTML untuk Form Pendaftaran KP -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Seminar Kerja Praktik</title>
    <link rel="stylesheet" href="style/stylependaftaran.css">
</head>
<body>
    <header>
        <h1>Seminar Kerja Praktik (KP) Prodi Teknik Kimia</h1>
    </header>
    
    <main>
        <h1>Seminar Kerja Praktik (KP) Prodi Teknik Kimia</h1>
        <p>Berikut form pendaftaran Seminar Kerja Praktik (TA) Program Studi Teknik Kimia UNSIKA</p>
        <script>
            window.onload = function() {
                // Jika status dikirimkan lewat URL
                const urlParams = new URLSearchParams(window.location.search);
                const status = urlParams.get('status');

                if (status === 'success') {
                    alert("Pendaftaran Seminar Kerja Praktik berhasil!");
                } else if (status === 'error') {
                    alert("Terjadi kesalahan. Silakan coba lagi.");
                }
            }
        </script>
        <form action="pendaftarankp.php" method="POST" class="form-seminar">
            <div class="form-section">
                <label for="tempat_kp">Tempat Kerja Praktik:</label>
                <input type="text" id="tempat_kp" name="tempat_kp" placeholder="Jawaban Anda">
            </div>

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
                <h2>Dokumen Pendukung</h2>
                <div class="form-group">
                    <label for="cover_laporan_link">Link Cover Laporan Kerja Praktik<span class="required">*</span></label>
                    <input type="text" id="cover_laporan_link" name="cover_laporan_link" placeholder="Masukkan link cover laporan KP" required>
                </div>

                <div class="form-group">
                    <label for="surat_permohonan_link">Link Surat Permohonan Seminar Kerja Praktik<span class="required">*</span></label>
                    <input type="text" id="surat_permohonan_link" name="surat_permohonan_link" placeholder="Masukkan link surat permohonan" required>
                </div>

                <div class="form-group">
                    <label for="bukti_seminar_link">Link Bukti Mengikuti Seminar KP<span class="required">*</span></label>
                    <input type="text" id="bukti_seminar_link" name="bukti_seminar_link" placeholder="Masukkan link bukti seminar" required>
                </div>
            </div>

            <div class="form-section">
                <label for="judul_khusus">Judul Laporan Khusus<span class="required">*</span></label>
                <input type="text" id="judul_khusus" name="judul_khusus" placeholder="Jawaban Anda" required>
            </div>

            <div class="form-section">
                <label for="judul_umum">Judul Laporan Umum<span class="required">*</span></label>
                <input type="text" id="judul_umum" name="judul_umum" placeholder="Jawaban Anda" required>
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
