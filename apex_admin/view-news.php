<?php
require_once '../connection.php';

$result = $conn->query("SELECT * FROM news ORDER BY news_date DESC");

$content = '<div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">';

while ($row = $result->fetch_assoc()) {
    $content .= '
     
    <div class="bg-white border border-gray-200 shadow-xl rounded-2xl overflow-hidden hover:shadow-2xl transition">
        <div class="relative h-64">
            <img src="../' . htmlspecialchars($row['image']) . '" alt="News Image" class="w-full h-full object-cover">
            <div class="absolute bottom-4 left-4 bg-blue-600 text-white px-4 py-1 text-sm rounded-full shadow">
                ' . date("D, M d, Y", strtotime($row['news_date'])) . '
            </div>
        </div>
        <div class="p-5">
            <h3 class="text-xl font-bold text-gray-800 mb-2">' . htmlspecialchars($row['title']) . '</h3>
            <p class="text-gray-600 text-sm">' . htmlspecialchars($row['description']) . '</p>
            <div class="mt-4 flex justify-between items-center">
                <a href="edit-news.php?id=' . $row['id'] . '" class="text-blue-600 font-medium hover:underline">Edit</a>
            </div>
        </div>
        <div class="mt-4 flex justify-between items-center">
  <a href="edit-news.php?id=' . $row['id'] . '" class="text-blue-600 font-medium hover:underline">Edit</a>
  <form method="POST" action="delete-news.php" onsubmit="return confirm(\'Are you sure?\');">
    <input type="hidden" name="id" value="' . $row['id'] . '">
    <button type="submit" class="text-red-600 font-medium hover:underline">Delete</button>
  </form>
</div>

    </div>';
}
$content .= '</div>';

include 'common.php';
