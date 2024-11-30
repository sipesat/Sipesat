<?php
include 'koneksi.php';

$query = "
    SELECT 
        ps.id, 
        m.nama AS nama_mahasiswa, 
        ps.jenis_seminar, 
        ps.cover_laporan_link, 
        ps.surat_permohonan_link, 
        ps.bukti_seminar_link,
        ps.abstrak_penelitian,
        ps.surat_penelitian,
        ps.krs_penelitian,
        ps.surat_persetujuan, 
        ps.transkrip_nilai, 
        ps.kwitansi_ukt, 
        ps.surat_keterangan, 
        ps.ijazah, 
        ps.pas_foto, 
        ps.loa, 
        ps.link_artikel
    FROM 
        pendaftaran_seminar ps
    JOIN 
        mahasiswa m ON ps.mahasiswa_id = m.id
";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<thead>
            <tr>
                <th>ID</th>
                <th>Nama Mahasiswa</th>
                <th>Jenis Seminar</th>
                <th>Aksi</th>
            </tr>
          </thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        $jenis_seminar = $row['jenis_seminar'];
        $onclick = "";

        // Tentukan dokumen yang akan ditampilkan berdasarkan jenis seminar
        if ($jenis_seminar === "Penelitian") {
            $onclick = "showDokumenPenelitian('{$row['abstrak_penelitian']}', '{$row['surat_penelitian']}', '{$row['krs_penelitian']}')";
        } elseif ($jenis_seminar === "Kerja Praktik") {
            $onclick = "showDokumenKP('{$row['cover_laporan_link']}', '{$row['surat_permohonan_link']}', '{$row['bukti_seminar_link']}')";
        } elseif ($jenis_seminar === "Tugas Akhir") {
            $onclick = "showDokumenTA('{$row['surat_persetujuan']}', '{$row['transkrip_nilai']}', '{$row['kwitansi_ukt']}', '{$row['surat_keterangan']}', '{$row['ijazah']}', '{$row['pas_foto']}', '{$row['loa']}', '{$row['link_artikel']}')";
        }

        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nama_mahasiswa']}</td>
                <td>{$row['jenis_seminar']}</td>
                <td><button onclick=\"$onclick\">Lihat Dokumen</button></td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>Tidak ada data dokumen</p>";
}
?>
