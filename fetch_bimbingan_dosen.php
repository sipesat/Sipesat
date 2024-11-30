<?php
include 'koneksi.php';

$query = "
    SELECT 
        b.id, 
        m.nama AS nama_mahasiswa, 
        m.npm, 
        b.jenis_bimbingan, 
        b.materi_bimbingan, 
        b.tanggal_bimbingan, 
        b.status 
    FROM 
        bimbingan b
    JOIN 
        mahasiswa m ON b.mahasiswa_id = m.id";

$result = $conn->query($query);

echo '<table>
        <thead>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>NPM</th>
                <th>Jenis Bimbingan</th>
                <th>Materi Bimbingan</th>
                <th>Tanggal Bimbingan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>';

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['nama_mahasiswa']}</td>
            <td>{$row['npm']}</td>
            <td>{$row['jenis_bimbingan']}</td>
            <td>{$row['materi_bimbingan']}</td>
            <td>" . ($row['tanggal_bimbingan'] ?: 'Belum Dijadwalkan') . "</td>
            <td id='status-{$row['id']}'>{$row['status']}</td>
            <td>
                <select id='status-select-{$row['id']}'>
                    <option value='Menunggu' " . ($row['status'] === 'Menunggu' ? 'selected' : '') . ">Menunggu</option>
                    <option value='Diterima' " . ($row['status'] === 'Diterima' ? 'selected' : '') . ">Diterima</option>
                    <option value='Selesai' " . ($row['status'] === 'Selesai' ? 'selected' : '') . ">Selesai</option>
                </select>
                <button onclick=\"updateStatus({$row['id']}, 'bimbingan', document.getElementById('status-select-{$row['id']}').value)\">Simpan</button>
                <button onclick=\"updateJadwal({$row['id']}, 'bimbingan')\">Ubah Jadwal</button>
            </td>
        </tr>";
}

echo '</tbody></table>';
?>
