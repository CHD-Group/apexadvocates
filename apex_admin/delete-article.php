<?php
require_once '../connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: admin.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch and remove image file
    $stmt = $conn->prepare("SELECT cover_image FROM articles WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($cover_image);
    $stmt->fetch();
    $stmt->close();

    if ($cover_image && file_exists("../" . $cover_image)) {
        unlink("../" . $cover_image);
    }

    // Delete article
    $stmt = $conn->prepare("DELETE FROM articles WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: view-article.php?status=deleted");
exit;
?>
