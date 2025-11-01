<?php
session_start();
include '../connection.php';

// Check if user is logged in (simple session check)
if (!isset($_SESSION['user_id'])) {
    header('Location: admin.php'); 
    exit;
}

// Fetch total news count
$result_news = $conn->query("SELECT COUNT(*) AS total_research FROM research");
$total_research = $result_news->fetch_assoc()['total_research'] ?? 0;

//fetch total moral count
$result_morals = $conn->query("SELECT COUNT(*) AS total_morals FROM news");
$total_morals = $result_morals->fetch_assoc()['total_morals'] ?? 0;

//fetch total articles count
$result_articles = $conn->query("SELECT COUNT(*) AS total_articles FROM articles");
$total_articles = $result_articles->fetch_assoc()['total_articles'] ?? 0;
$conn->close();

$content = '
<div class="max-w-4xl mx-auto mt-16 p-6 bg-white rounded-2xl shadow-2xl">
  <h1 class="text-3xl font-bold text-center text-blue-700 mb-10">Admin Dashboard</h1>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-blue-700 text-white rounded-xl p-8 shadow-md hover:shadow-xl transition duration-300 text-center">
      <h2 class="text-5xl font-bold mb-2">' . $total_research . '</h2>
      <p class="text-xl uppercase tracking-wide font-semibold">Total Research</p>
    </div>
   <div class="bg-blue-700 text-white rounded-xl p-8 shadow-md hover:shadow-xl transition duration-300 text-center">
      <h2 class="text-5xl font-bold mb-2">' . $total_morals . '</h2>
      <p class="text-xl uppercase tracking-wide font-semibold">Total News</p>
  </div>
    <div class="bg-blue-700 text-white rounded-xl p-8 shadow-md hover:shadow-xl transition duration-300 text-center">
      <h2 class="text-5xl font-bold mb-2">' . $total_articles . '</h2>
      <p class="text-xl uppercase tracking-wide font-semibold">Total Articles</p>
    </div>
</div>
';

include 'common.php';
?>
