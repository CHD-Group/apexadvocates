<?php
require_once '../connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: admin.php');
    exit;
}

$result = $conn->query("SELECT * FROM articles ORDER BY article_date DESC");

$content = '
<div class="max-w-7xl mx-auto mt-10 p-4">
  <h2 class="text-3xl font-bold mb-6 text-center text-indigo-700">Manage Articles</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
';

while ($row = $result->fetch_assoc()) {
    $content .= '
    <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-5 flex flex-col justify-between">
      <img src="../' . htmlspecialchars($row['cover_image']) . '" alt="Cover Image" class="w-full h-48 object-cover rounded-lg mb-4">
      <div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">' . htmlspecialchars($row['title']) . '</h3>
        <p class="text-gray-600 text-sm mb-3 line-clamp-3">' . htmlspecialchars($row['short_description']) . '</p>
        <p class="text-gray-500 text-xs mb-4">Published on: ' . htmlspecialchars($row['article_date']) . '</p>
      </div>
      <div class="flex justify-between mt-auto">
        <a href="edit-article.php?id=' . $row['id'] . '" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600 transition">Edit</a>
        <a href="delete-article.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this article?\')" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition">Delete</a>
      </div>
    </div>';
}

$content .= '
  </div>
</div>
';

include 'common.php';
?>
