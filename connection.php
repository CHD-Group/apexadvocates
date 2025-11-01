<?php
$host = 'localhost';      
$user = 'root';        
$password = '';     
$dbname = 'apex_advocates';          

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connection passed";
?>
