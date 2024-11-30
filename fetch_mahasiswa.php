<?php
include 'koneksi.php';

$query = "SELECT id, nama, npm, email, angkatan FROM mahasiswa";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>NPM</th>
                <th>Email</th>
                <th>Angkatan</th>
            </tr>
          </thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nama']}</td>
                <td>{$row['npm']}</td>
                <td>{$row['email']}</td>
                <td>{$row['angkatan']}</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>Tidak ada data mahasiswa</p>";
}
?>
