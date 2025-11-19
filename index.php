<?php
require_once 'connection.php'; 

// Fetch latest 3 research papers
$sql = "SELECT id, title, cover_image, pdf_path, publication_date 
        FROM research 
        ORDER BY publication_date DESC 
        LIMIT 3";
$result = $conn->query($sql);

//Fetch latest 3 media entries
$sql_media = "SELECT id, title, image, source_logo, external_link, news_date 
        FROM news 
        ORDER BY news_date DESC 
        LIMIT 3";
$result_media = $conn->query($sql_media);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Apex Advocates</title>
  <!-- favicon -->
    <link rel="icon" href="Image/india.jpg" type="image/x-icon">
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

      <ul id="navMenu" class="flex flex-wrap justify-center gap-6 py-3 text-[17px] font-medium">
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

 <!-- Hero Section -->
<section class="hero-section relative h-[220px] sm:h-[300px] lg:h-[850px]">
  <img src="Image/india.jpg" alt="india" class=" w-full h-full object-cover object-top">
</section>
<h2 class="text-2xl lg:text-4xl text-center font-bold text-black-600 mb-4 mt-4 lg:mt-10">Apex Advocates | Your partner for legal peace</h2>

<!-- Latest Research Papers Section -->
<section class="py-16 bg-gradient-to-b from-gray-50 to-white">
  <h2 class="text-center text-4xl md:text-4xl font-extrabold mb-12 text-[#5b3429] tracking-wide">
    Research Papers
  </h2>

  <div class="max-w-7xl mx-auto grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 px-6">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
          <a href="<?php echo htmlspecialchars($row['pdf_path']); ?>" target="_blank">
            <img src="<?php echo htmlspecialchars($row['cover_image']); ?>" 
                 alt="<?php echo htmlspecialchars($row['title']); ?>" 
                 class="w-full h-64 object-cover hover:scale-105 transition-transform duration-500">
          </a>
          <div class="p-6">
            <a href="<?php echo htmlspecialchars($row['pdf_path']); ?>" target="_blank">
              <h3 class="font-semibold text-lg text-gray-800 mb-3 leading-snug hover:text-[#5b3429] transition-colors">
                <?php echo htmlspecialchars($row['title']); ?>
              </h3>
            </a>
            <p class="text-sm text-gray-500 font-medium">
              Published on <?php echo date('F j, Y', strtotime($row['publication_date'])); ?>
            </p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="text-center col-span-3 text-gray-500 italic">
        No research papers available at the moment.
      </div>
    <?php endif; ?>
  </div>
</section>

<!--Latest media pages -->
<section class="py-16 bg-gradient-to-b from-gray-50 to-white">
  <h2 class="text-center text-4xl md:text-4xl font-extrabold mb-12 text-[#5b3429] tracking-wide">
    Latest Media
  </h2>

  <div class="max-w-7xl mx-auto grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 px-6">
    <?php if ($result_media && $result_media->num_rows > 0): ?>
      <?php while ($row = $result_media->fetch_assoc()): ?>
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
          <a href="<?php echo htmlspecialchars($row['external_link']); ?>" target="_blank">
            <img src="<?php echo htmlspecialchars($row['image']); ?>" 
                 alt="<?php echo htmlspecialchars($row['title']); ?>" 
                 class="w-full h-64 object-cover hover:scale-105 transition-transform duration-500">
          </a>
          <div class="p-6">
            <a href="<?php echo htmlspecialchars($row['external_link']); ?>" target="_blank">
              <h3 class="font-semibold text-lg text-gray-800 mb-3 leading-snug hover:text-[#5b3429] transition-colors">
                <?php echo htmlspecialchars($row['title']); ?>
              </h3>
            </a>
            <p class="text-sm text-gray-500 font-medium">
              Published on <?php echo date('F j, Y', strtotime($row['news_date'])); ?>
            </p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="text-center col-span-3 text-gray-500 italic">
        No media entries available at the moment.
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- Why Choose Apex Advocates Section -->
<section id="awards" class="py-16 bg-gradient-to-b from-gray-50 to-white">
  <h2 class="text-center text-3xl md:text-4xl font-extrabold mb-12 text-[#5b3429] tracking-wide">
    Why Choose Apex Advocates
  </h2>

  <div class="max-w-6xl mx-auto grid grid-cols-1 sm:grid-cols-2 gap-8 px-6">
    <!-- Award Item -->
    <div data-aos="fade-up" class="bg-white p-8 rounded-2xl shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-[#5b3429]">
      <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center md:text-left border-b border-brown-200 pb-2">PROPERTY LAW</h3>
      <p class="text-gray-600 text-sm leading-relaxed text-justify">
        Property law concerns laws about the transfer and inheritance of property. We provide excellent drafting & documentation of Wills, Gift Deeds, Family arrangement, lease & rent agreements etc. We also provide registration of the documents like Sale Deeds etc.
      </p>
    </div>

    <div data-aos="fade-up" data-aos-delay="100" class="bg-white p-8 rounded-2xl shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-[#5b3429]">
      <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center md:text-left border-b border-brown-200 pb-2">EMPLOYMENT LAW</h3>
      <p class="text-gray-600 text-sm leading-relaxed text-justify">
        Employment law concerns the rights & duties of employer and employee and all issues related to employment.
      </p>
    </div>

    <div data-aos="fade-up" data-aos-delay="200" class="bg-white p-8 rounded-2xl shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-[#5b3429]">
      <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center md:text-left border-b border-brown-200 pb-2">FAMILY LAW / LAW OF INHERITANCE</h3>
      <p class="text-gray-600 text-sm leading-relaxed text-justify">
        Law of Inheritance deals with Intestate Succession, Testamentary Succession and Partition. The laws are primarily based on customary principles derived from uncodified personal laws. The Parliament has codified some of them i.e. The Indian Succession Act, 1925, The Hindu Succession Act, 1956, The Partition Act, 1893. The Firm provides complete legal assistance in relation to drafting of Wills, deeds, and other legal instruments. The Firm also drafts the legal documents i.e., Applications, Pleadings and Petition and appears before the Court of Law on behalf of its clients.
      </p>
    </div>

    <div data-aos="fade-up" data-aos-delay="300" class="bg-white p-8 rounded-2xl shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-[#5b3429]">
      <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center md:text-left border-b border-brown-200 pb-2">CRIMINAL LAW</h3>
      <p class="text-gray-600 text-sm leading-relaxed text-justify">
        Criminal law covers all the proceedings before Executive and Judicial Magistrates in relation to law and order, and trials for all kind of offences punishable under Penal Laws. Regular Bail, Anticipatory Bail, Appeal, Quashing Petition, Special Leave Petition, etc. comes under the purview of Criminal Law. The Firm on behalf of its clients deals with entire criminal proceedings including appearance, arguments, cross-examination before the Court of Law. The Firm also provides legal opinion and advice in relation to the questions of law.
      </p>
    </div>

    <div data-aos="fade-up" data-aos-delay="400" class="bg-white p-8 rounded-2xl shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-[#5b3429]">
      <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center md:text-left border-b border-brown-200 pb-2">MATRIMONIAL LAWS</h3>
      <p class="text-gray-600 text-sm leading-relaxed text-justify">
        “Marriage” a sacred ceremony, a bond tying not only the pair but the entire family, thus itself becomes a law equally important as any other law. India a secular country, with variety of religions and not having a uniform code, Muslim marriages are governed by Muslim Personal Law/ Shariat law whereas Hindu marriages are governed by Hindu Marriage Act, 1955 and similarly Christian Marriage Act, 1872 applies to Christians. In case of Special cases Special Marriage Act, 1954 applies universally to all religions. “Marriage is a contract” “Contracts are meant to be broken” therefore rules and regulations regarding troubled marriages are enshrined under Indian Divorce Act, 1869.
      </p>
    </div>

    <div data-aos="fade-up" data-aos-delay="500" class="bg-white p-8 rounded-2xl shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 border-t-4 border-t-[#5b3429]">
      <h3 class="text-xl font-semibold text-gray-800 mb-2 text-center md:text-left border-b border-brown-200 pb-2">ANIMAL LAW</h3>
      <p class="text-gray-600 text-sm leading-relaxed text-justify">
        Animals are innocent and mute creatures, but when they come into contact with human beings, humans must behave appropriately. India has incorporated laws to prevent the cruelties of animals — it is a punishable offence to cause pain, killing, poisoning, maiming or torturing of animals. The Wildlife Protection Act, 1972 prohibits teasing, molesting, injuring, feeding or causing disturbance to any animal and the same is a punishable offence.
      </p>
    </div>
  </div>
</section>


<footer style="background-color: #f2f2f2; padding: 20px 0; text-align: center; font-size: 16px; color: #030303;">
  <div>
    &copy; 2023 apexadvocates.in. All rights reserved. Developed by <a href="https://www.globalsouthstrategies.com/" target="_blank" style="color: #fc0909; text-decoration: none;">Global South Strategies</a>
  </div>
</footer>

<script src="script.js"></script>
<!-- Include AOS (Animate on Scroll) Library -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init({
    once: true, // Animation happens only once
    duration: 800, // Animation duration in ms
    easing: 'ease-in-out'
  });
</script>
       
</body>
</html>
