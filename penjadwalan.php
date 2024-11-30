<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['npm'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjadwalan</title>
    <link rel="stylesheet" href="style/stylepenjadwalan.css">
</head>
<body>

<h1>Pilih kategori untuk melihat jadwal:</h1>
<div class="button-container">
    <button class="btn-seminar" onclick="filterSeminar('Kerja Praktik')">Kerja Praktik</button>
    <button class="btn-seminar" onclick="filterSeminar('Penelitian')">Penelitian</button>
    <button class="btn-seminar" onclick="filterSeminar('Tugas Akhir')">Tugas Akhir</button>
    <button class="btn-bimbingan" onclick="filterBimbingan()">Bimbingan</button>
</div>

<!-- Tabel dengan dua thead berbeda untuk seminar dan bimbingan -->
<div class="table-container">
    <table id="schedule-table">
        <thead id="seminar-head">
            <tr>
                <th>Nama Mahasiswa</th>
                <th>NPM</th>
                <th>Dosen Pembimbing</th>
                <th>Tanggal Seminar</th>
                <th>Ruangan Seminar</th>
                <th>Status</th>
            </tr>
        </thead>
        <thead id="bimbingan-head" style="display: none;">
            <tr>
                <th>Nama Mahasiswa</th>
                <th>NPM</th>
                <th>Dosen Pembimbing</th>
                <th>Jenis Bimbingan</th>
                <th>Tanggal Bimbingan</th>
                <th>Topik Bimbingan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <!-- Data dari AJAX akan dimuat di sini -->
        </tbody>
    </table>
</div>

<script>
// Fungsi untuk memfilter dan menampilkan data seminar berdasarkan jenis seminar
function filterSeminar(type) {
    // Tampilkan thead untuk seminar, sembunyikan untuk bimbingan
    document.getElementById('seminar-head').style.display = 'table-header-group';
    document.getElementById('bimbingan-head').style.display = 'none';

    fetch(`fetch_seminar.php?type=${type}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = ''; // Kosongkan tabel

            data.forEach(item => {
                const row = `<tr>
                    <td>${item.nama_mahasiswa}</td>
                    <td>${item.npm}</td>
                    <td>${item.nama_dosen}</td>
                    <td>${item.tanggal_seminar || 'Belum Dijadwalkan' }</td>
                    <td>${item.ruangan_seminar || 'Belum Ditentukan'}</td>
                    <td>${item.status}</td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error:', error));
}

// Fungsi untuk memfilter dan menampilkan data bimbingan
function filterBimbingan() {
    // Tampilkan thead untuk bimbingan, sembunyikan untuk seminar
    document.getElementById('seminar-head').style.display = 'none';
    document.getElementById('bimbingan-head').style.display = 'table-header-group';

    fetch('fetch_bimbingan.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = ''; // Kosongkan tabel

            data.forEach(item => {
                const row = `<tr>
                    <td>${item.nama_mahasiswa}</td>
                    <td>${item.npm}</td>
                    <td>${item.nama_dosen}</td>
                    <td>${item.jenis_bimbingan}</td>
                    <td>${item.tanggal_bimbingan || 'Belum Dijadwalkan'}</td>
                    <td>${item.materi_bimbingan}</td>
                    <td>${item.status}</td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error('Error:', error));
}
</script>

</body>
</html>
