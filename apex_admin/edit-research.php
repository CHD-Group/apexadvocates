<?php
require_once '../connection.php';

if (!isset($_GET['id'])) {
    die("Research ID missing.");
}

$id = intval($_GET['id']);
$error = '';
$message = '';

// Fetch existing research
$stmt = $conn->prepare("SELECT * FROM research WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) {
    die("Research entry not found.");
}

// Slug generator function
function createSlug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    return $slug;
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $publication_date = $_POST['publication_date'];
    $cover_image = $_POST['old_cover_image'];
    $pdf_path = $_POST['old_pdf_path'];
    $slug = createSlug($title);

    // Upload new image
    if (!empty($_FILES['cover_image']['name'])) {
        $imgName = basename($_FILES['cover_image']['name']);
        $imgTmp = $_FILES['cover_image']['tmp_name'];
        $targetImg = '../Image/' . $imgName;
        if (move_uploaded_file($imgTmp, $targetImg)) {
            $cover_image = 'Image/' . $imgName;
        } else {
            $error = "Failed to upload new image.";
        }
    }

    // Upload new PDF
    if (!empty($_FILES['pdf_path']['name'])) {
        $pdfName = basename($_FILES['pdf_path']['name']);
        $pdfTmp = $_FILES['pdf_path']['tmp_name'];
        $targetPDF = '../pdfs/' . $pdfName;
        if (move_uploaded_file($pdfTmp, $targetPDF)) {
            $pdf_path = 'pdfs/' . $pdfName;
        } else {
            $error = "Failed to upload new PDF.";
        }
    }

    // Update database
    if (!$error) {
        $stmt = $conn->prepare("UPDATE research SET title=?, publication_date=?, cover_image=?, pdf_path=? WHERE id=?");
        $stmt->bind_param("ssssi", $title, $publication_date, $cover_image, $pdf_path, $id);
        if ($stmt->execute()) {
            $message = "Research updated successfully.";
            // Refresh the slug after update
            $data['slug'] = $slug;
        } else {
            $error = "Update failed: " . $stmt->error;
        }
    }
}

$content = '
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded-2xl mt-10">
  <h2 class="text-2xl font-bold mb-6">Edit Research</h2>

  ' . ($error ? '<p class="text-red-600 mb-4">' . $error . '</p>' : '') . '
  ' . ($message ? '<p class="text-green-600 mb-4">' . $message . '</p>' : '') . '

  <form method="POST" enctype="multipart/form-data" class="space-y-4">
    <div>
      <label class="font-semibold">Title</label>
      <input type="text" name="title" value="' . htmlspecialchars($data['title']) . '" required class="w-full border p-2 rounded" />
    </div>

    <div>
      <label class="font-semibold">Slug</label>
      <input type="text" value="' . htmlspecialchars($data['slug']) . '" class="w-full border p-2 rounded bg-gray-100" readonly />
    </div>

    <div>
      <label class="font-semibold">Publication Date</label>
      <input type="date" name="publication_date" value="' . htmlspecialchars($data['publication_date']) . '" class="w-full border p-2 rounded" />
    </div>

    <div>
      <label class="font-semibold">Current Cover Image</label><br>
      <img src="../' . htmlspecialchars($data['cover_image']) . '" alt="Image" class="w-32 my-2 rounded shadow" />
      <input type="file" name="cover_image" class="w-full">
      <input type="hidden" name="old_cover_image" value="' . htmlspecialchars($data['cover_image']) . '" />
    </div>

    <div>
      <label class="font-semibold">Current PDF</label><br>
      <a href="../' . htmlspecialchars($data['pdf_path']) . '" target="_blank" class="text-blue-600 underline">View Current PDF</a>
      <input type="file" name="pdf_path" class="w-full mt-2">
      <input type="hidden" name="old_pdf_path" value="' . htmlspecialchars($data['pdf_path']) . '" />
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Research</button>
  </form>
</div>
';

include 'common.php';
