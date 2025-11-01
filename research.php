<?php
require_once 'connection.php'; // adjust path if needed

// Fetch all research papers (latest first)
$sql = "SELECT id, title, cover_image, pdf_path, publication_date 
        FROM research 
        ORDER BY publication_date DESC";
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

   <!-- Research Papers Section -->
  <section class="py-10 px-6 md:px-12 lg:px-24 bg-gradient-to-br from-[#fff8f5] to-[#f2e9e4]">
    <h2 class="text-center text-3xl font-extrabold mb-16 text-[#4b2e25] tracking-wide uppercase">
      Research Papers
    </h2>

    <?php if ($result && $result->num_rows > 0): ?>
      <div class="grid gap-10 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="relative group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-[#e8dcd2]">
            
            <!-- Image section -->
            <div class="relative overflow-hidden">
              <a href="detailed-research.php?id=<?php echo $row['id']; ?>">
                <img src="<?php echo htmlspecialchars($row['cover_image']); ?>" 
                     alt="<?php echo htmlspecialchars($row['title']); ?>" 
                     class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
              </a>
              <!-- Subtle overlay -->
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
              <div class="absolute bottom-3 left-3 bg-white/90 text-[#4b2e25] text-xs font-semibold px-3 py-1 rounded-full shadow">
                <?php echo date('F j, Y', strtotime($row['publication_date'])); ?>
              </div>
            </div>

            <!-- Text content -->
            <div class="p-6">
              <a href="detailed-research.php?id=<?php echo $row['id']; ?>">
                <h3 class="text-2xl font-bold mb-3 text-[#4b2e25] leading-snug hover:text-[#2d1a14] transition-all duration-300">
                  <?php echo htmlspecialchars($row['title']); ?>
                </h3>
              </a>
            </div>

            <!--PDF download link-->
            <div class="p-3 text-center border-t border-[#e8dcd2]">
              <a href="<?php echo htmlspecialchars($row['pdf_path']); ?>" target="_blank" 
                 class="inline-flex items-center gap-2 text-[#8b5e3c] font-semibold hover:text-[#4b2e25] transition-all">
                 Download Full Paper <i class="fa-solid fa-arrow-down-to-line text-sm"></i>
              </a>
            </div>

            <!-- Accent border -->
            <div class="absolute top-0 left-0 w-1 h-full bg-[#c39a76] group-hover:h-0 transition-all duration-700"></div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p class="text-center text-gray-500 italic mt-12">No research papers available at the moment.</p>
    <?php endif; ?>
  </section>


  <!-- Footer -->
  <footer class="bg-gray-100 py-6 text-center text-gray-700 text-sm">
    &copy; <?php echo date("Y"); ?> apexadvocates.in. All rights reserved. 
    Developed by <a href="https://www.globalsouthstrategies.com/" class="text-red-600 hover:underline" target="_blank">Global South Strategies</a>
  </footer>

</body>
</html>
