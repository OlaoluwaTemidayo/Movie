<?php
// Start session to manage login state
session_start();

// Include the database connection
include 'db.php';

// Get the movie slug from the URL and sanitize it
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// Fetch movie details using the slug
$sql_movie = "SELECT * FROM movies WHERE slug = ?";
$stmt_movie = $conn->prepare($sql_movie);
$stmt_movie->bind_param("s", $slug);
$stmt_movie->execute();
$result_movie = $stmt_movie->get_result();
$movie = $result_movie->fetch_assoc();

if (!$movie) {
    echo "Movie not found.";
    exit;
}

// Fetch comments for the movie
$sql_comments = "SELECT * FROM comments WHERE movie_id = ?";
$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("i", $movie['id']);
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title']); ?> | Netflix</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #141414;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        p {
            font-size: 1rem;
            color: #b3b3b3;
        }

        a {
            text-decoration: none;
            color: #e50914;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-post-comment {
            display: inline-block;
            background-color: #e50914;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            border-radius: 4px;
            margin-top: 10px;
            text-decoration: none;
        }

        .btn-post-comment:hover {
            background-color: #f40612;
        }

        hr {
            border: 1px solid #333;
            margin: 20px 0;
        }

        .comments {
            margin-top: 20px;
        }

        .comment {
            background-color: #333;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .comment p {
            color: #b3b3b3;
            font-size: 0.9rem;
        }

        .comment strong {
            color: #fff;
        }

        .comment small {
            color: #737373;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
            border-radius: 4px;
            resize: none;
        }

        input[type="submit"] {
            background-color: #e50914;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #f40612;
        }

        .movie-info {
            margin-bottom: 40px;
        }

        .movie-info p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="movie-info">
            <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
            <p><?php echo htmlspecialchars($movie['description']); ?></p>
            <p>Release Year: <?php echo htmlspecialchars($movie['release_year']); ?></p>
        </div>

        <hr>

        <div class="comments">
            <h2>Comments</h2>

            <?php if ($result_comments->num_rows > 0): ?>
                <?php while ($comment = $result_comments->fetch_assoc()): ?>
                    <div class="comment">
                        <p><strong><?php echo htmlspecialchars($comment['name']); ?>:</strong></p>
                        <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                        <p><small><?php echo htmlspecialchars($comment['created_at']); ?></small></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        </div>

        <hr>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Display the comment form for logged-in users -->
            <h2>Post a Comment</h2>
            <form action="post_comment.php" method="POST">
                <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                <input type="hidden" name="slug" value="<?php echo htmlspecialchars($movie['slug']); ?>"> <!-- Include slug for redirection -->

                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly><br><br>
                
                <label for="comment">Comment:</label><br>
                <textarea id="comment" name="comment" rows="4" required></textarea><br><br>
                
                <input type="submit" value="Post Comment">
            </form>
        <?php else: ?>
            <!-- Display a login link for users who are not logged in -->
            <p><a href="login.php">Log in</a> to post a comment.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
