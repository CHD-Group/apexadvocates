<?php
require_once '../connection.php';

$result = $conn->query("SELECT * FROM research ORDER BY publication_date DESC");

$content = '
<div class="max-w-7xl mx-auto mt-10 p-4">
  <h2 class="text-3xl font-bold mb-6">Manage Research Entries</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
';

while ($row = $result->fetch_assoc()) {
    $content .= '
    <div class="bg-white rounded-xl shadow p-4 flex flex-col justify-between">
      <img src="../' . htmlspecialchars($row['cover_image']) . '" alt="Cover" class="w-full h-48 object-cover rounded-md mb-4" />
      <div>
        <h3 class="text-xl font-bold mb-2">' . htmlspecialchars($row['title']) . '</h3>
        <p class="text-gray-500 text-xs mb-4">Published on: ' . htmlspecialchars($row['publication_date']) . '</p>
      </div>
      <div class="flex justify-between mt-auto">
        <a href="edit-research.php?id=' . $row['id'] . '" class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">Edit</a>
        <a href="delete-research.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this entry?\')" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">Delete</a>
      </div>
    </div>';
}

$content .= '</div></div>';

include 'common.php';
