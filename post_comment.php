<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit;
}

// Include the database connection
include 'db.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $movie_id = $_POST['movie_id'];
    $slug = $_POST['slug']; // Get the movie slug for redirection
    $name = $_SESSION['username']; // Use the session username
    $comment = trim($_POST['comment']);

    // Validate the form data
    if (!empty($comment)) {
        // Insert the comment into the database
        $sql = "INSERT INTO comments (movie_id, user_id, name, comment) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $movie_id, $_SESSION['user_id'], $name, $comment);

        if ($stmt->execute()) {
            // Redirect back to the movie page
            header("Location: movie.php?slug=" . urlencode($slug)); // Use urlencode for safety
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Comment field cannot be empty.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
