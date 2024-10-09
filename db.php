<?php
$host = "localhost";  // or your database host
$user = "root";       // your MySQL username
$pass = "";           // your MySQL password
$dbname = "movie_db"; // the database you created

$conn = new mysqli($host, $user, $pass, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>