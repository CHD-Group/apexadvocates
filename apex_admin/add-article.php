<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: admin.php');
    exit;
}

// Create unique slug
function createSlug($string, $conn) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    $baseSlug = $slug;
    $i = 1;

    while (true) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM articles WHERE slug = ?");
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) break;
        $slug = $baseSlug . '-' . $i;
        $i++;
    }

    return $slug;
}

// Message handling
$message = '';
$messageType = '';

if (isset($_GET['status'])) {
    $messageType = $_GET['status'] === 'success' ? 'success' : 'error';
    $message = $_GET['status'] === 'success'
        ? "Article added successfully!"
        : "Error: " . htmlspecialchars($_GET['msg'] ?? 'Unknown error');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $short_desc = trim($_POST['short_description']);
    $long_desc = $_POST['long_description'];
    $article_date = $_POST['article_date'];

    if (!$title || !$short_desc || !$long_desc || !$article_date || empty($_FILES['cover_image']['name'])) {
        header("Location: add-article.php?status=error&msg=" . urlencode("All required fields must be filled and cover image uploaded."));
        exit();
    }

    $slug = createSlug($title, $conn);

    // Upload cover image
    $coverImageName = basename($_FILES['cover_image']['name']);
    $coverImageTmp = $_FILES['cover_image']['tmp_name'];
    $uniqueCoverName = uniqid() . '-' . $coverImageName;

    $relativePath = 'Image/' . $uniqueCoverName;
    $absolutePath = __DIR__ . '/../' . $relativePath;

    if (!move_uploaded_file($coverImageTmp, $absolutePath)) {
        error_log("UPLOAD FAILED: TMP: $coverImageTmp => DEST: $absolutePath");
        header("Location: add-article.php?status=error&msg=" . urlencode("Cover image upload failed."));
        exit();
    }

    $coverImagePath = $relativePath;

    // Insert article
    $stmt = $conn->prepare("INSERT INTO articles (title, slug, cover_image, short_description, article_date, long_description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $slug, $coverImagePath, $short_desc, $article_date, $long_desc);

    if ($stmt->execute()) {
        $article_id = $stmt->insert_id;

        // Handle optional multiple images
        if (!empty($_FILES['detail_images']['name'][0])) {
            foreach ($_FILES['detail_images']['name'] as $index => $imgName) {
                $imgTmp = $_FILES['detail_images']['tmp_name'][$index];
                $uniqueName = uniqid() . '-' . basename($imgName);
                $imgDest = '../Image/' . $uniqueName;

                if (move_uploaded_file($imgTmp, $imgDest)) {
                    $imgPath = 'Image/' . $uniqueName;
                    $imgStmt = $conn->prepare("INSERT INTO article_images (article_id, image_path) VALUES (?, ?)");
                    $imgStmt->bind_param("is", $article_id, $imgPath);
                    $imgStmt->execute();
                    $imgStmt->close();
                }
            }
        }

        header("Location: add-article.php?status=success");
        exit();
    } else {
        header("Location: add-article.php?status=error&msg=" . urlencode($stmt->error));
        exit();
    }
}
?>

<?php
$content = '
<div class="flex justify-center items-center min-h-screen bg-gray-100 px-4">
  <form method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-xl w-full max-w-3xl">
    <h2 class="text-3xl font-bold text-center mb-6 text-indigo-700">Add New Article</h2>

    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Title</label>
      <input type="text" name="title" required class="w-full border border-gray-300 px-4 py-2 rounded" />
    </div>

    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Short Description</label>
      <textarea name="short_description" rows="3" required class="w-full border border-gray-300 px-4 py-2 rounded"></textarea>
    </div>

    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Article Date</label>
      <input type="date" name="article_date" required class="w-full border border-gray-300 px-4 py-2 rounded" />
    </div>

    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Long Description</label>
      <textarea name="long_description" id="long_description" rows="6" required class="w-full border border-gray-300 px-4 py-2 rounded"></textarea>
    </div>

    <div class="mb-4">
      <label class="font-semibold text-gray-700 block mb-1">Cover Image</label>
      <input type="file" name="cover_image" required class="w-full" />
    </div>

    <div class="mb-6">
      <label class="font-semibold text-gray-700 block mb-1">Detailed Images (optional, multiple)</label>
      <input type="file" name="detail_images[]" multiple class="w-full" />
    </div>

    <button type="submit" class="bg-indigo-600 text-white py-3 px-6 rounded hover:bg-indigo-700 w-full">Submit</button>
  </form>
</div>

' . (!empty($message) ? '
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="messageModal">
    <div class="bg-white rounded-xl border-l-8 p-6 max-w-md w-full ' . ($messageType === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700') . ' shadow-lg">
      <p class="mb-6 text-lg font-semibold">' . htmlspecialchars($message) . '</p>
      <button onclick="closeModal()" class="px-6 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">Close</button>
    </div>
  </div>
' : '') . '

<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
  CKEDITOR.replace("long_description");

  function closeModal() {
    const modal = document.getElementById("messageModal");
    if (modal) modal.style.display = "none";
  }
</script>
';

include 'common.php';
?>
