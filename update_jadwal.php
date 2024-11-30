<?php
include 'koneksi.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$type = $data['type'];
$date = $data['date'];

$query = ($type === 'seminar') ? 
    "UPDATE pendaftaran_seminar SET tanggal_seminar = ? WHERE id = ?" : 
    "UPDATE bimbingan SET tanggal_bimbingan = ? WHERE id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("si", $date, $id);

echo json_encode(['success' => $stmt->execute()]);
?>
