<?php
require_once 'connection.php';

// Validate and fetch paper by ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM research WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Research paper not found.");
}

$paper = $result->fetch_assoc();
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

  <!-- Research Content -->
  <section class="max-w-5xl mx-auto bg-white rounded-3xl shadow-2xl p-8 md:p-12 mb-16">
    <!-- Cover Image -->
    <div class="flex justify-center">
      <img src="<?php echo htmlspecialchars($paper['cover_image']); ?>" 
           alt="Research Cover" 
           class="rounded-2xl w-full max-w-2xl h-[500px] object-cover shadow-md">
    </div>

    <!-- Title -->
    <h2 class="text-3xl md:text-4xl font-bold text-center text-[#4b2e25] mt-8 mb-3 leading-snug">
      <?php echo htmlspecialchars($paper['title']); ?>
    </h2>

    <!-- Full screen button for pdf-viewer -->
    <div class=" mb-4">
      <button id="fullscreenBtn" class="inline-flex items-center gap-2 bg-[#4b2e25] text-white px-4 py-2 rounded-full text-lg hover:bg-[#2d1a14] transition-all shadow-md">
        <i class="fa-solid fa-expand"></i> Full Screen
      </button>
    </div>

    <!-- Description -->
    <?php if (!empty($paper['description'])): ?>
      <p class="text-gray-700 text-lg leading-relaxed mb-10 text-justify">
        <?php echo nl2br(htmlspecialchars($paper['description'])); ?>
      </p>
    <?php endif; ?>

    <!-- PDF Viewer -->
    <?php if (!empty($paper['pdf_path'])): ?>
      <div class="relative w-full h-full aspect-[4/3] md:aspect-[16/9] overflow-hidden rounded-xl shadow-lg mb-8">
        <iframe src="<?php echo htmlspecialchars($paper['pdf_path']); ?>#toolbar=1" 
                class="w-full h-full rounded-xl"
                frameborder="0"
                allowfullscreen>
        </iframe>
      </div>

      <!-- Download Button -->
      <div class="text-center">
        <a href="<?php echo htmlspecialchars($paper['pdf_path']); ?>" download 
           class="inline-flex items-center gap-2 bg-[#4b2e25] text-white px-6 py-3 rounded-full text-lg hover:bg-[#2d1a14] transition-all shadow-md">
          <i class="fa-solid fa-download"></i> Download PDF
        </a>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-500 italic">PDF not available for this research paper.</p>
    <?php endif; ?>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-100 py-6 text-center text-gray-700 text-sm">
    &copy; <?php echo date("Y"); ?> apexadvocates.in. All rights reserved. 
    Developed by <a href="https://www.globalsouthstrategies.com/" class="text-red-600 hover:underline" target="_blank">Global South Strategies</a>
  </footer>

<script>
    //fullscreen button functionality
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    fullscreenBtn.addEventListener('click', () => {
        const iframe = document.querySelector('iframe');
        if (iframe.requestFullscreen) {
            iframe.requestFullscreen();
        } else if (iframe.mozRequestFullScreen) { /* Firefox */
            iframe.mozRequestFullScreen();
        } else if (iframe.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
            iframe.webkitRequestFullscreen();
        } else if (iframe.msRequestFullscreen) { /* IE/Edge */
            iframe.msRequestFullscreen();
        }
    });
</script>
</body>
</html>
