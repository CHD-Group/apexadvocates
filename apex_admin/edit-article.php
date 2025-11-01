<?php
require_once '../connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: admin.php');
    exit;
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM articles WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();

if (!$article) {
    $content = "<p class='text-center text-red-600 mt-10'>Article not found!</p>";
    include 'common.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $short_desc = trim($_POST['short_description']);
    $long_desc = $_POST['long_description'];
    $article_date = $_POST['article_date'];

    $cover_image = $article['cover_image'];
    if (!empty($_FILES['cover_image']['name'])) {
        $imageName = uniqid() . '-' . basename($_FILES['cover_image']['name']);
        $imagePath = '../Image/' . $imageName;
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $imagePath);
        $cover_image = 'Image/' . $imageName;
    }

    $stmt = $conn->prepare("UPDATE articles SET title=?, short_description=?, long_description=?, article_date=?, cover_image=? WHERE id=?");
    $stmt->bind_param("sssssi", $title, $short_desc, $long_desc, $article_date, $cover_image, $id);

    if ($stmt->execute()) {
        header("Location: view-article.php?status=updated");
        exit;
    } else {
        header("Location: edit-article.php?id=$id&status=error");
        exit;
    }
}

$content = '
<div class="flex justify-center items-center min-h-screen bg-gray-100 px-4">
  <form method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-xl w-full max-w-3xl">
    <h2 class="text-3xl font-bold text-center mb-6 text-indigo-700">Edit Article</h2>

    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Title</label>
      <input type="text" name="title" value="'.htmlspecialchars($article['title']).'" required class="w-full border border-gray-300 px-4 py-2 rounded" />
    </div>

    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Short Description</label>
      <textarea name="short_description" rows="3" required class="w-full border border-gray-300 px-4 py-2 rounded">'.htmlspecialchars($article['short_description']).'</textarea>
    </div>

    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Article Date</label>
      <input type="date" name="article_date" value="'.htmlspecialchars($article['article_date']).'" required class="w-full border border-gray-300 px-4 py-2 rounded" />
    </div>

    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Long Description</label>
      <textarea name="long_description" id="long_description" rows="6" required class="w-full border border-gray-300 px-4 py-2 rounded">'.htmlspecialchars($article['long_description']).'</textarea>
    </div>

    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Current Cover Image</label><br>
      <img src="../'.htmlspecialchars($article['cover_image']).'" class="h-24 rounded mb-3 shadow">
      <input type="file" name="cover_image" class="w-full" />
    </div>
    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Detailed Images</label><br>
      ';
      $stmt = $conn->prepare("SELECT * FROM article_images WHERE article_id=?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $images = $stmt->get_result();

      while ($img = $images->fetch_assoc()) {
          $content .= '<img src="../'.htmlspecialchars($img['image_path']).'" class="h-24 rounded mb-3 shadow">';
      }

      $content .= '
      <input type="file" name="detail_images[]" multiple class="w-full" />
    </div>

    <button type="submit" class="bg-indigo-600 text-white py-3 px-6 rounded hover:bg-indigo-700 w-full">Update Article</button>
  </form>
</div>

<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script> CKEDITOR.replace("long_description"); </script>
';

include 'common.php';
?>
