<?php
include 'koneksi.php'; // Pastikan koneksi sudah benar

// Query untuk mengambil data bimbingan dengan informasi dosen dan mahasiswa
$query = "
    SELECT 
        m.nama AS nama_mahasiswa, 
        m.npm, 
        d.nama AS nama_dosen, 
        b.jenis_bimbingan, 
        b.tanggal_bimbingan, 
        b.materi_bimbingan, 
        b.status 
    FROM 
        bimbingan b
    JOIN 
        mahasiswa m ON b.mahasiswa_id = m.id
    JOIN 
        dosen d ON b.dosen_id = d.id
";

$result = $conn->query($query);

if ($result) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode(["error" => "Query gagal dijalankan"]);
}
?>
