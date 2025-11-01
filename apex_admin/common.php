<?php
require_once '../connection.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard - NGO</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-100">

  <!-- HEADER -->
  <header class="bg-blue-900 text-white h-16 flex items-center justify-between px-6 fixed top-0 left-0 right-0 z-50 shadow">
    <h1 class="text-xl font-bold">APEX ADVOCATES Admin Panel</h1>
    <div class="text-sm">Welcome, Admin</div>
  </header>

  <!-- SIDEBAR + CONTENT WRAPPER -->
  <div class="flex pt-16">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white h-screen shadow-lg fixed top-16 left-0 z-40">
      <nav class="flex flex-col p-4 space-y-2">
        <a href="dashboard.php" class="text-gray-800 hover:bg-blue-100 rounded px-3 py-2 flex items-center">
          <span class="material-icons mr-3 text-blue-600">dashboard</span> Dashboard
        </a>
        <a href="add-research.php" class="text-gray-800 hover:bg-blue-100 rounded px-3 py-2 flex items-center">
          <span class="material-icons mr-3 text-blue-600">article</span> Add Research
        </a>
        <a href="view-research.php" class="text-gray-800 hover:bg-blue-100 rounded px-3 py-2 flex items-center">
          <span class="material-icons mr-3 text-blue-600">article</span> View Research
        </a>
        <a href="add-article.php" class="text-gray-800 hover:bg-blue-100 rounded px-3 py-2 flex items-center">
          <span class="material-icons mr-3 text-blue-600">forum</span> Add Articles
        </a>
        <a href="view-article.php" class="text-gray-800 hover:bg-blue-100 rounded px-3 py-2 flex items-center">
          <span class="material-icons mr-3 text-blue-600">forum</span> View Articles
        </a>
        <a href="add-news.php" class="text-gray-800 hover:bg-blue-100 rounded px-3 py-2 flex items-center">
          <span class="material-icons mr-3 text-blue-600">psychology</span> Add News
        </a>
        <a href="view-news.php" class="text-gray-800 hover:bg-blue-100 rounded px-3 py-2 flex items-center">
          <span class="material-icons mr-3 text-blue-600">psychology</span> View News
        </a>
        <a href="logout.php" class="text-gray-800 hover:bg-blue-100 rounded px-3 py-2 flex items-center">
          <span class="material-icons mr-3 text-blue-600">logout</span> Logout
        </a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
   <main class="ml-64 p-6 w-full">
      <?php
      // This is where page content will be injected
      if (isset($content)) {
          echo $content;
      }
      ?>
    </main>

  </div>

</body>
</html>
