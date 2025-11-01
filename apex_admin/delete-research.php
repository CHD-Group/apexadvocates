<?php
require_once '../connection.php';

if (!isset($_GET['id'])) {
    die("Missing ID.");
}

$id = intval($_GET['id']);

// Optional: Fetch and delete files from disk
$stmt = $conn->prepare("SELECT cover_image, pdf_path FROM research WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result) {
    $img = '../' . $result['cover_image'];
    $pdf = '../' . $result['pdf_path'];

    if (file_exists($img)) unlink($img);
    if (file_exists($pdf)) unlink($pdf);
}

// Delete from DB
$stmt = $conn->prepare("DELETE FROM research WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: view-research.php?deleted=1");
    exit;
} else {
    die("Failed to delete.");
}
