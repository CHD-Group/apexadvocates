<?php
require_once '../connection.php';

$message = '';
$messageType = '';

// Get news ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing ID.");
}
$id = (int)$_GET['id'];

// Fetch existing news data
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();

if (!$news) {
    die("News not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? '';
    $externalLink = $_POST['external_link'] ?? '';

    $imagePath = $news['image'];
    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $imageTmp = $_FILES['image']['tmp_name'];
        $imagePath = 'Image/' . $imageName;

        if (!move_uploaded_file($imageTmp, '../' . $imagePath)) {
            $message = "Failed to upload image.";
            $messageType = "error";
        }
    }

    $sourceLogoPath = $news['source_logo'];
    if (!empty($_FILES['source_logo']['name'])) {
        $sourceLogoName = basename($_FILES['source_logo']['name']);
        $sourceLogoTmp = $_FILES['source_logo']['tmp_name'];
        $sourceLogoPath = 'Image/' . $sourceLogoName;

        if (!move_uploaded_file($sourceLogoTmp, '../' . $sourceLogoPath)) {
            $message = "Failed to upload source logo.";
            $messageType = "error";
        }
    }

    if (!$message) {
        $sql = "UPDATE news SET title=?, description=?, image=?, source_logo=?, news_date=?, external_link=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $title, $description, $imagePath, $sourceLogoPath, $date, $externalLink, $id);

        if ($stmt->execute()) {
            header("Location: edit-news.php?id=$id&status=success");
            exit();
        } else {
            $message = "Database error: " . $stmt->error;
            $messageType = "error";
        }
    }
}

// Check status
if (isset($_GET['status']) && $_GET['status'] === 'success') {
    $message = "News updated successfully!";
    $messageType = "success";
}

// Form UI
$content = '
<div class="flex items-center justify-center min-h-[calc(100vh-4rem)] bg-gray-50">
  <form method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-lg mx-4">
    <h2 class="text-3xl font-extrabold mb-8 text-gray-900 text-center">Edit News</h2>

    <div class="mb-6">
      <label for="title" class="block text-gray-700 font-semibold mb-2">News Title</label>
      <input type="text" name="title" id="title" value="' . htmlspecialchars($news['title']) . '" required
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
    </div>

    <div class="mb-6">
      <label for="description" class="block text-gray-700 font-semibold mb-2">Description</label>
      <textarea name="description" id="description" rows="5"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-600">'
        . htmlspecialchars($news['description']) .
      '</textarea>
    </div>

    <div class="mb-6">
      <label class="block text-gray-700 font-semibold mb-2">Current Image</label>
      <img src="../' . $news['image'] . '" alt="News Image" class="w-32 h-auto mb-2">
      <input type="file" name="image" accept="image/*" class="w-full text-gray-600" />
    </div>

    <div class="mb-6">
      <label for="date" class="block text-gray-700 font-semibold mb-2">News Date</label>
      <input type="date" name="date" id="date" value="' . htmlspecialchars($news['news_date']) . '"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
    </div>

    <div class="mb-6">
      <label for="external_link" class="block text-gray-700 font-semibold mb-2">External Link</label>
      <input type="url" name="external_link" id="external_link" value="' . htmlspecialchars($news['external_link']) . '"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
    </div>

    <div class="mb-6">
      <label class="block text-gray-700 font-semibold mb-2">Current Source Logo</label>';
if ($news['source_logo']) {
    $content .= '<img src="../' . $news['source_logo'] . '" alt="Logo" class="w-20 h-auto mb-2">';
}
$content .= '
      <input type="file" name="source_logo" accept="image/*" class="w-full text-gray-600" />
    </div>

    <button type="submit"
      class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">Update News</button>
  </form>
</div>
';

// Modal for messages
if ($message) {
    $modalColor = $messageType === "success" ? "bg-green-100 border-green-500 text-green-700" : "bg-red-100 border-red-500 text-red-700";
    $content .= '
    <div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl border-l-8 p-6 max-w-md w-full ' . $modalColor . ' shadow-lg">
        <p class="mb-6 text-lg font-semibold">' . htmlspecialchars($message) . '</p>
        <button onclick="closeModal()" class="px-6 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">Close</button>
      </div>
    </div>';
}

$content .= '<script>function closeModal() {
  document.getElementById("messageModal").style.display = "none";
}</script>';

include 'common.php';
?>
