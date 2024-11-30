<?php
include 'koneksi.php';

$query = "
    SELECT 
        ps.id, 
        m.nama AS nama_mahasiswa, 
        m.npm,
        ps.jenis_seminar, 
        ps.ruangan_seminar, 
        ps.tanggal_seminar, 
        ps.status 
    FROM 
        pendaftaran_seminar ps
    JOIN 
        mahasiswa m ON ps.mahasiswa_id = m.id";

$result = $conn->query($query);

echo '<table>
        <thead>
            <tr>
                <th>Nama Mahasiswa</th>
                <th>NPM</th>
                <th>Jenis Seminar</th>
                <th>Ruangan Seminar</th>
                <th>Tanggal Seminar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>';

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['nama_mahasiswa']}</td>
            <td>{$row['npm']}</td>
            <td>{$row['jenis_seminar']}</td>
            <td>" . ($row['ruangan_seminar'] ?: 'Belum Ditentukan') . "</td>
            <td>" . ($row['tanggal_seminar'] ?: 'Belum Dijadwalkan') . "</td>
            <td id='status-{$row['id']}'>{$row['status']}</td>
            <td>
                <select id='status-select-{$row['id']}'>
                    <option value='Ditolak' " . ($row['status'] === 'Ditolak' ? 'selected' : '') . ">Ditolak</option>
                    <option value='Diterima' " . ($row['status'] === 'Diterima' ? 'selected' : '') . ">Diterima</option>
                    <option value='Selesai' " . ($row['status'] === 'Selesai' ? 'selected' : '') . ">Selesai</option>
                </select>
                <button onclick=\"updateStatus({$row['id']}, 'seminar', document.getElementById('status-select-{$row['id']}').value)\">Simpan</button>
                <button onclick=\"updateJadwal({$row['id']}, 'seminar')\">Ubah Jadwal</button>
                <button onclick=\"updateRuangan({$row['id']})\">Ubah Ruangan</button>
            </td>
        </tr>";
}

echo '</tbody></table>';
?>
