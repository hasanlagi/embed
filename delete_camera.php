<?php
include 'db.php';
$id = $_GET['id'] ?? 0;

$stmt = $db->prepare("DELETE FROM cameras WHERE id = ?");
$stmt->execute([$id]);

// Hapus file embed
$embedFile = __DIR__ . "/embed/camera-$id.html";
if (file_exists($embedFile)) {
    unlink($embedFile);
}

header("Location: index.php");
exit;
