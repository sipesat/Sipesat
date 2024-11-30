<?php
include 'koneksi.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$type = $data['type'];
$status = $data['status'];

$query = ($type === 'seminar') ? 
    "UPDATE pendaftaran_seminar SET status = ? WHERE id = ?" : 
    "UPDATE bimbingan SET status = ? WHERE id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $id);

echo json_encode(['success' => $stmt->execute()]);
?>
