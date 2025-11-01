<?php
require_once '../connection.php';

// Initialize message variables
$message = '';
$messageType = '';

// Check if redirected with status message
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = "News added successfully!";
        $messageType = "success";
    } elseif ($_GET['status'] === 'error') {
        $message = "Error: " . htmlspecialchars($_GET['msg'] ?? 'Unknown error');
        $messageType = "error";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? '';
    $externalLink = $_POST['external_link'] ?? '';

    // Validate required fields (basic)
    if (!$title || !$description || !$date || empty($_FILES['image']['name'])) {
        // Redirect with error message
        header("Location: add-news.php?status=error&msg=" . urlencode("Please fill in all required fields and select an image."));
        exit();
    }

    // Upload main image
    $imageName = basename($_FILES['image']['name']);
    $imageTmp = $_FILES['image']['tmp_name'];
    $imagePath = 'Image/' . $imageName;

    if (!move_uploaded_file($imageTmp, '../' . $imagePath)) {
        header("Location: add-news.php?status=error&msg=" . urlencode("Failed to upload image."));
        exit();
    }

    // Upload optional source logo
    $sourceLogoPath = '';
    if (!empty($_FILES['source_logo']['name'])) {
        $sourceLogoName = basename($_FILES['source_logo']['name']);
        $sourceLogoTmp = $_FILES['source_logo']['tmp_name'];
        $sourceLogoPath = 'Image/' . $sourceLogoName;

        if (!move_uploaded_file($sourceLogoTmp, '../' . $sourceLogoPath)) {
            header("Location: add-news.php?status=error&msg=" . urlencode("Failed to upload source logo."));
            exit();
        }
    }

    // Prepare insert statement with external_link
    $sql = "INSERT INTO news (title, description, image, source_logo, news_date, external_link) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        header("Location: add-news.php?status=error&msg=" . urlencode("Database error: " . $conn->error));
        exit();
    }
    $stmt->bind_param("ssssss", $title, $description, $imagePath, $sourceLogoPath, $date, $externalLink);

    if ($stmt->execute()) {
        // Redirect to avoid resubmission on refresh
        header("Location: add-news.php?status=success");
        exit();
    } else {
        header("Location: add-news.php?status=error&msg=" . urlencode($stmt->error));
        exit();
    }
}
?>

<?php
$content = '
<div class="flex items-center justify-center min-h-[calc(100vh-4rem)] bg-gray-50">
  <form method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-3xl shadow-2xl w-full max-w-lg mx-4">
    <h2 class="text-3xl font-extrabold mb-8 text-gray-900 text-center">Add News</h2>
    <div class="mb-6">
      <label for="title" class="block text-gray-700 font-semibold mb-2">News Title</label>
      <input type="text" name="title" id="title" required placeholder="Enter news title" 
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
    </div>
    <div class="mb-6">
      <label for="description" class="block text-gray-700 font-semibold mb-2">Description</label>
      <textarea name="description" id="description" rows="5" placeholder="Enter news description"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
    </div>
    <div class="mb-6">
      <label for="image" class="block text-gray-700 font-semibold mb-2">News Image</label>
      <input type="file" name="image" id="image" accept="image/*" required
        class="w-full text-gray-600" />
    </div>
    <div class="mb-6">
      <label for="date" class="block text-gray-700 font-semibold mb-2">News Date</label>
      <input type="date" name="date" id="date"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
    </div>
    <div class="mb-6">
      <label for="external_link" class="block text-gray-700 font-semibold mb-2">External News URL (optional)</label>
      <input type="url" name="external_link" id="external_link" placeholder="https://example.com/news-article"
        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" />
    </div>
    <div class="mb-8">
      <label for="source_logo" class="block text-gray-700 font-semibold mb-2">Source Logo (optional)</label>
      <input type="file" name="source_logo" id="source_logo" accept="image/*"
        class="w-full text-gray-600" />
    </div>
    <button type="submit"
      class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition">Add News</button>
  </form>
</div>
';

// Modal popup for success or error
if ($message) {
    $modalColor = $messageType === "success" 
        ? "bg-green-100 border-green-500 text-green-700" 
        : "bg-red-100 border-red-500 text-red-700";

    $content .= '
    <div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-xl border-l-8 p-6 max-w-md w-full ' . $modalColor . ' shadow-lg">
        <p class="mb-6 text-lg font-semibold">' . htmlspecialchars($message) . '</p>
        <button onclick="closeModal()" class="px-6 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">Cancel</button>
      </div>
    </div>
    ';
}

$content .= '
<script>
  function closeModal() {
    document.getElementById("messageModal").style.display = "none";
  }
</script>
';

include 'common.php';
?>
