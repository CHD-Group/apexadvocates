<?php
require_once 'connection.php';

// Fetch all articles (latest first)
$sql = "SELECT id, title, slug, cover_image, short_description, article_date FROM articles ORDER BY article_date DESC";
$result = $conn->query($sql);
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
  <header class="sticky top-0 bg-white z-10">
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
        <li class="dropdown"><a href="media.php" class="dropdown-toggle">MEDIA</a></li>
<li class="dropdown"><a href="contact.html" class="dropdown-toggle">CONTACT</a></li>

      </ul>
    </nav>
  </header>
  <hr class="border-gray-300"/>

<!-- Articles Section -->
<section class="px-6 md:px-12 lg:px-24 py-12 bg-[#faf9f7]">
  <h2 class="text-center text-3xl md:text-4xl font-extrabold mb-10 text-[#4b2e25] uppercase">
    Articles
  </h2>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="space-y-10">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-10 bg-transparent">

          <!-- Left: Image -->
          <div class="w-full md:w-1/3 flex justify-center">
            <a href="detailed-article/<?php echo urlencode($row['slug']); ?>" class="block">
              <img 
                src="<?php echo htmlspecialchars($row['cover_image']); ?>" 
                alt="<?php echo htmlspecialchars($row['title']); ?>" 
                class="w-64 h-64 object-cover rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500"
              />
            </a>
          </div>

          <!-- Right: Content -->
          <div class="w-full md:w-2/3 flex flex-col justify-center space-y-3">

            <a href="detailed-article/<?php echo urlencode($row['slug']); ?>" class="text-4xl font-bold text-black hover:text-[#7b4f37] transition">
              <?php echo htmlspecialchars($row['title']); ?>
            </a>

            <?php if (!empty($row['short_description'])): ?>
              <p class="text-red-700 leading-relaxed">
                <?php echo nl2br(htmlspecialchars($row['short_description'])); ?>
              </p>
            <?php endif; ?>
          </div>

        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-center text-gray-500 italic mt-12">No articles available at the moment.</p>
  <?php endif; ?>
</section>

  <!-- Footer -->
<footer style="background-color: #f2f2f2; padding: 20px 0; text-align: center; font-size: 16px; color: #030303; border-top: 1px solid #ccc;">
  <div>
    &copy; 2023 apexadvocates.in. All rights reserved. Developed by <a href="https://www.globalsouthstrategies.com/" target="_blank" style="color: #fc0909; text-decoration: none;">Global South Strategies</a>
  </div>
</footer>

  <script src="script.js"></script>
  </body>
</html>