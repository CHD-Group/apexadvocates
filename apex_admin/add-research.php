<?php
require_once '../connection.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $publication_date = $_POST['publication_date'];

    $cover_image_path = '';
    $pdf_path = '';

    // ==== COVER IMAGE UPLOAD ====
    if (!empty($_FILES['cover_image']['name'])) {
        $imgName = basename($_FILES['cover_image']['name']);
        $imgExt = pathinfo($imgName, PATHINFO_EXTENSION);
        $uniqueImgName = uniqid('cover_', true) . '.' . $imgExt;

        $relativeImgPath = 'Image/' . $uniqueImgName;
        $absoluteImgPath = __DIR__ . '/../' . $relativeImgPath;

        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $absoluteImgPath)) {
            $cover_image_path = $relativeImgPath;
        } else {
            $error = 'Failed to upload cover image.';
        }
    }

    // ==== PDF UPLOAD ====
    if (!$error && !empty($_FILES['pdf_file']['name'])) {
        $pdfName = basename($_FILES['pdf_file']['name']);
        $pdfExt = pathinfo($pdfName, PATHINFO_EXTENSION);
        $uniquePdfName = uniqid('file_', true) . '.' . $pdfExt;

        $relativePdfPath = 'pdfs/' . $uniquePdfName;
        $absolutePdfPath = __DIR__ . '/../' . $relativePdfPath;

        if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $absolutePdfPath)) {
            $pdf_path = $relativePdfPath;
        } else {
            $error = 'Failed to upload PDF file.';
        }
    }

    // ==== INSERT INTO DATABASE ====
    if (!$error) {
        $stmt = $conn->prepare("INSERT INTO research (title, cover_image, pdf_path, publication_date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $cover_image_path, $pdf_path, $publication_date);

        if ($stmt->execute()) {
            $message = "Research paper added successfully.";
        } else {
            $error = "Database error: " . $stmt->error;
        }
    }
}

// ==== HTML CONTENT ====
$content = '
<div class="max-w-3xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-lg">
  <h2 class="text-2xl font-bold mb-6 text-gray-800">Add Research Paper</h2>';

if ($message) {
    $content .= '<p class="text-green-600 font-semibold mb-4">' . htmlspecialchars($message) . '</p>';
} elseif ($error) {
    $content .= '<p class="text-red-600 font-semibold mb-4">' . htmlspecialchars($error) . '</p>';
}

$content .= '
  <form method="POST" enctype="multipart/form-data" class="space-y-6">

    <!-- Title -->
    <div>
      <label class="block font-semibold mb-1 text-gray-700">Title</label>
      <input type="text" name="title" required class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" />
    </div>

    <!-- Publication Date -->
    <div>
      <label class="block font-semibold mb-1 text-gray-700">Publication Date</label>
      <input type="date" name="publication_date" required class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" />
    </div>

    <!-- Cover Image -->
    <div>
      <label class="block font-semibold mb-1 text-gray-700">Cover Image</label>
      <input type="file" name="cover_image" accept="image/*" required class="w-full border border-gray-300 px-3 py-2 rounded-lg" />
    </div>

    <!-- PDF File -->
    <div>
      <label class="block font-semibold mb-1 text-gray-700">Research PDF</label>
      <input type="file" name="pdf_file" accept="application/pdf" required class="w-full border border-gray-300 px-3 py-2 rounded-lg" />
    </div>

    <!-- Submit Button -->
    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
      Submit Research Paper
    </button>
  </form>
</div>
';

include 'common.php';
?>
