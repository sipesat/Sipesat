<?php
include 'koneksi.php';

$type = $_GET['type'] ?? ''; // Mendapatkan jenis seminar dari parameter URL

if (empty($type)) {
    echo json_encode(['error' => 'Jenis seminar tidak ditemukan']);
    exit;
}

$query = "
    SELECT 
        ps.id,
        m.nama AS nama_mahasiswa,
        m.npm,
        d1.nama AS dosen_utama,
        d2.nama AS dosen_kedua,
        ps.tanggal_seminar,
        ps.ruangan_seminar,
        ps.status
    FROM pendaftaran_seminar ps
    JOIN mahasiswa m ON ps.mahasiswa_id = m.id
    LEFT JOIN dosen d1 ON ps.pembimbing_id = d1.id
    LEFT JOIN dosen d2 ON ps.pembimbing_kedua_id = d2.id
    WHERE ps.jenis_seminar = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $type);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    // Menambahkan pengecekan dosen utama dan dosen kedua
    $dosenPembimbingUtama = $row['dosen_utama'] ? $row['dosen_utama'] : "-";
    $dosenPembimbingKedua = $row['dosen_kedua'] ? $row['dosen_kedua'] : "-";

    $data[] = [
        'nama_mahasiswa' => $row['nama_mahasiswa'],
        'npm' => $row['npm'],
        'nama_dosen' => $dosenPembimbingUtama . ($dosenPembimbingKedua !== "-" ? ", " . $dosenPembimbingKedua : ""),
        'tanggal_seminar' => $row['tanggal_seminar'],
        'ruangan_seminar' => $row['ruangan_seminar'],
        'status' => $row['status']
    ];
}

// Mengirimkan data dalam format JSON yang benar
header('Content-Type: application/json');
echo json_encode($data);
?>
