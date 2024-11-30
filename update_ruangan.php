<?php
include 'koneksi.php';

// Set header untuk JSON response
header('Content-Type: application/json');

// Pastikan method-nya POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $id = $input['id'] ?? null;
    $ruangan = $input['ruangan'] ?? null;

    if ($id && $ruangan) {
        // Query untuk update ruangan_seminar
        $stmt = $conn->prepare("UPDATE pendaftaran_seminar SET ruangan_seminar = ? WHERE id = ?");
        $stmt->bind_param("si", $ruangan, $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Ruangan seminar berhasil diperbarui.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui ruangan seminar.']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'ID atau ruangan tidak valid.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
