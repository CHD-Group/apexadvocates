<?php
require_once 'connection.php';

// Initialize article
$article = null;

if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    // If using slug (SEO-friendly URL)
    $slug = $_GET['slug'];
    $stmt = $conn->prepare("SELECT * FROM articles WHERE slug = ?");
    $stmt->bind_param("s", $slug);
} elseif (isset($_GET['id']) && !empty($_GET['id'])) {
    // Fallback: if you still use ID-based URL
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->bind_param("i", $id);
} else {
    echo "<p class='text-center text-red-500 mt-10'>Invalid article request.</p>";
    exit;
}

// Execute and fetch
$stmt->execute();
$result = $stmt->get_result();
$article = $result->fetch_assoc();
$stmt->close();

// Handle missing article
if (!$article) {
    echo "<p class='text-center text-gray-500 mt-10'>Article not found.</p>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apex Advocates</title>
  <!-- favicon -->
    <link rel="icon" href="Image/Dr. Edmond Fernandes            Official.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Neuton Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Neuton:ital,wght@0,200;0,300;0,400;0,700;0,800;1,400&display=swap" rel="stylesheet">
     <!-- Font Awesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css"> 
</head>
<body class="font-[Slabo]">
   <header class="text-center">
        <a href="index.php"><div class="logo mx-auto w-[380px] lg:w-[700px] mb-2 mt-7">
            <h1 class="text-3xl lg:text-5xl text-black-500 uppercase">Apex Advocates</h1>
        </div></a>
        <div class="header-text">
            
        </div>
        </header>
 <!-- Navigation Bar -->
  <header class="sticky top-0 z-10 bg-white">
    <nav class="navbar">
      <button class="menu-toggle" id="navToggle" aria-label="Toggle menu">
        <i class="fas fa-bars"></i>
      </button>

      <ul id="navMenu">
        <li class="dropdown">
          <a href="index.php" class="dropdown-toggle">HOME</a>
        </li>
        <li class="dropdown">
          <a href="about.html" class="dropdown-toggle">ABOUT US</a>
        </li>
        <li class="dropdown">
          <a href="service.html" class="dropdown-toggle">AREAS OF SERVICE</a>
        </li>
        <li class="dropdown">
          <a href="achievements.html" class="dropdown-toggle">ACHIEVEMENTS</a>
        </li>
         <li class="dropdown">
          <a href="articles.php" class="dropdown-toggle">ARTICLES</a>
        </li>
        <li class="dropdown">
          <a href="research.php" class="dropdown-toggle">RESEARCH PAPERS</a>
        </li>
        <li class="dropdown">
          <a href="media.php" class="dropdown-toggle">MEDIA</a>
        </li>
        <li class="dropdown">
          <a href="contact.html" class="dropdown-toggle">CONTACT</a>
        </li>
      </ul>
    </nav>
  </header>
  <hr class="border-gray-300"/>

  <!-- Article Detail Section -->
<section class="px-6 md:px-12 lg:px-24 py-12 bg-[#faf9f7] min-h-screen">
  <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6 md:p-10">

    <!-- Cover Image -->
    <div class="flex justify-center mb-8">
      <img 
        src="<?php echo htmlspecialchars($article['cover_image']); ?>" 
        alt="<?php echo htmlspecialchars($article['title']); ?>" 
        class="rounded-xl shadow-lg w-full md:w-3/4 h-auto object-cover"
      />
    </div>

    <!-- Title & Date -->
    <div class="text-center mb-8">
      <h1 class="text-3xl md:text-4xl font-extrabold text-[#3b2316] mb-2">
        <?php echo htmlspecialchars($article['title']); ?>
      </h1>
      <p class="text-sm text-gray-500">
        Published on <?php echo date('F j, Y', strtotime($article['article_date'])); ?>
      </p>
    </div>

    <!-- Long Description -->
    <div class="prose max-w-none text-gray-800 leading-relaxed">
      <?php echo $article['long_description']; ?>
    </div>

  </div>
</section>

  <!-- Footer -->
  <footer class="bg-gray-100 py-6 text-center text-gray-700 text-sm">
    &copy; <?php echo date("Y"); ?> apexadvocates.in. All rights reserved. 
    Developed by <a href="https://www.globalsouthstrategies.com/" class="text-red-600 hover:underline" target="_blank">Global South Strategies</a>
  </footer>

  </body>
</html>