<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['nidn'])) {
    header("Location: index.php");
    exit;
}

$nidn = $_SESSION['nidn'];
$stmt = $conn->prepare("SELECT * FROM dosen WHERE nidn = ?");
$stmt->bind_param("s", $nidn);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin SIPESAT</title>
    <link rel="stylesheet" href="style/styledosen.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>Admin SIPESAT</h1>
        <div class="logout-container">
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </header>
    <main>
        <div class="dashboard">
            <div class="box" onclick="loadContent('fetch_mahasiswa.php')">
                <h3>Daftar Mahasiswa</h3>
                <p>Melihat data mahasiswa yang terdaftar.</p>
            </div>
            <div class="box" onclick="loadContent('fetch_seminar_dosen.php')">
                <h3>Seminar</h3>
                <p>Atur status dan jadwal seminar.</p>
            </div>
            <div class="box" onclick="loadContent('fetch_bimbingan_dosen.php')">
                <h3>Bimbingan</h3>
                <p>Atur status dan jadwal bimbingan.</p>
            </div>
            <div class="box" onclick="loadContent('fetch_dokumen.php')">
                <h3>Dokumen</h3>
                <p>Melihat dokumen yang diunggah mahasiswa.</p>
            </div>
        </div>

        <div id="content-area">
            <!-- Konten akan dimuat di sini -->
        </div>

    </main>

    <footer>
        <p>&copy; 2024 SIPESAT. All rights reserved.</p>
    </footer>

    <script>
        // Fungsi untuk memuat konten menggunakan AJAX
        function loadContent(url) {
            $('#content-area').html('<p>Loading...</p>'); // Placeholder saat data dimuat
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $('#content-area').html(data);
                },
                error: function(xhr, status, error) {
                    $('#content-area').html('<p>Terjadi kesalahan saat memuat data.</p>');
                    console.error('Error:', error);
                }
            });
        }

        function updateStatus(id, type, status) {
            fetch('update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, type, status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Status berhasil diubah ke ${status}`);
                        if (type === 'seminar') loadContent('fetch_seminar_dosen.php');
                        else loadContent('fetch_bimbingan_dosen.php');
                    } else {
                        alert('Gagal mengubah status');
                    }
                });
        }

        function updateJadwal(id, type) {
            const date = prompt('Masukkan tanggal baru (YYYY-MM-DD):');
            if (date) {
                fetch('update_jadwal.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, type, date })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Jadwal berhasil diubah');
                            if (type === 'seminar') loadContent('fetch_seminar_dosen.php');
                            else loadContent('fetch_bimbingan_dosen.php');
                        } else {
                            alert('Gagal mengubah jadwal');
                        }
                    });
            }
        }

        function updateRuangan(id) {
            const ruangan = prompt('Masukkan ruangan seminar:');
            if (ruangan) {
                fetch('update_ruangan.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, ruangan })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Ruangan seminar berhasil diperbarui');
                        loadContent('fetch_seminar_dosen.php'); // Refresh data seminar
                    } else {
                        alert('Gagal memperbarui ruangan seminar: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui ruangan seminar.');
                });
            }
        }

        function showDokumenPenelitian(abstrak, suratPenelitian, krs) {
            const dokumenHTML = `
                <h2>Dokumen Penelitian</h2>
                <table>
                    <tr><th>Abstrak Penelitian</th><td>${abstrak || 'Tidak Ada'}</td></tr>
                    <tr><th>Surat Penelitian</th><td>${suratPenelitian || 'Tidak Ada'}</td></tr>
                    <tr><th>KRS</th><td>${krs || 'Tidak Ada'}</td></tr>
                </table>
            `;
            $('#content-area').html(dokumenHTML);
        }

        function showDokumenKP(cover, suratPermohonan, buktiSeminar) {
            const dokumenHTML = `
                <h2>Dokumen Kerja Praktik</h2>
                <table>
                    <tr><th>Cover Laporan</th><td>${cover || 'Tidak Ada'}</td></tr>
                    <tr><th>Surat Permohonan</th><td>${suratPermohonan || 'Tidak Ada'}</td></tr>
                    <tr><th>Bukti Seminar</th><td>${buktiSeminar || 'Tidak Ada'}</td></tr>
                </table>
            `;
            $('#content-area').html(dokumenHTML);
        }

        // Fungsi untuk menampilkan dokumen jenis Tugas Akhir
        function showDokumenTA(suratPersetujuan, transkripNilai, kwitansiUKT, suratKeterangan, ijazah, pasFoto, loa, linkArtikel) {
            const dokumenHTML = `
                <h2>Dokumen Tugas Akhir</h2>
                <table>
                    <tr><th>Surat Persetujuan</th><td>${suratPersetujuan || 'Tidak Ada'}</td></tr>
                    <tr><th>Transkrip Nilai</th><td>${transkripNilai || 'Tidak Ada'}</td></tr>
                    <tr><th>Kwitansi UKT</th><td>${kwitansiUKT || 'Tidak Ada'}</td></tr>
                    <tr><th>Surat Keterangan</th><td>${suratKeterangan || 'Tidak Ada'}</td></tr>
                    <tr><th>Ijazah</th><td>${ijazah || 'Tidak Ada'}</td></tr>
                    <tr><th>Pas Foto</th><td>${pasFoto || 'Tidak Ada'}</td></tr>
                    <tr><th>LOA</th><td>${loa || 'Tidak Ada'}</td></tr>
                    <tr><th>Link Artikel</th><td>${linkArtikel || 'Tidak Ada'}</td></tr>
                </table>
            `;
            $('#content-area').html(dokumenHTML);
        }
    </script>
</body>
</html>
