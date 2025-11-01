<?php
require_once 'connection.php';

// Fetch all news, latest first
$sql = "SELECT * FROM news ORDER BY news_date DESC";
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

 <!-- News Section -->
<section class="px-6 md:px-12 lg:px-24 py-10">
  <h2 class="text-center text-3xl md:text-4xl font-extrabold mb-10 text-[#4b2e25] uppercase">
    Latest News
  </h2>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="space-y-10">
      <?php while ($row = $result->fetch_assoc()): ?>
        <?php 
          $linkStart = !empty($row['external_link']) ? '<a href="' . htmlspecialchars($row['external_link']) . '" target="_blank" class="block group">' : '<div>';
          $linkEnd = !empty($row['external_link']) ? '</a>' : '</div>';
        ?>

        <div class="flex flex-col md:flex-row items-center md:items-start bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 border border-[#e7d9ce]">
          
          <!-- Left Section (Image + Date) -->
          <div class="w-full md:w-1/3 relative">
            <?php echo $linkStart; ?>
              <img src="<?php echo htmlspecialchars($row['image']); ?>" 
                   alt="News Image" 
                   class="w-full h-64 md:h-full object-cover transition-transform duration-500 group-hover:scale-105" />
              <div class="absolute bottom-3 left-3 bg-white/90 px-3 py-1 rounded-full text-xs font-semibold text-[#4b2e25] shadow">
                <?php echo date('F j, Y', strtotime($row['news_date'])); ?>
              </div>
            <?php echo $linkEnd; ?>
          </div>

          <!-- Right Section (Content) -->
          <div class="w-full md:w-2/3 p-6 flex flex-col justify-center">
            <?php echo $linkStart; ?>
              <h3 class="text-4xl font-bold text-[#3b2316] mb-4 leading-snug hover:text-[#6e3e2a] transition">
                <?php echo htmlspecialchars($row['title']); ?>
              </h3>
            <?php echo $linkEnd; ?>

            <?php if (!empty($row['source_logo'])): ?>
              <div class="flex flex-col gap-2">
                <span class="text-lg font-semibold text-gray-500">IN THE NEWS BY</span>
                <img src="<?php echo htmlspecialchars($row['source_logo']); ?>" 
                     alt="Source Logo" 
                     class="w-24 h-auto object-contain" />
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-center text-gray-500 italic mt-12">No news available at the moment.</p>
  <?php endif; ?>
</section>


  <!-- Footer -->
  <footer class="bg-gray-100 py-6 text-center text-gray-700 text-sm mt-10">
    &copy; <?php echo date("Y"); ?> apexadvocates.in. All rights reserved. 
    Developed by 
    <a href="https://www.globalsouthstrategies.com/" class="text-red-600 hover:underline" target="_blank">
      Global South Strategies
    </a>
  </footer>
</body>
</html>
