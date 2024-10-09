<?php
// Start the session to manage user authentication
session_start();

// Include the database connection
include 'db.php';

// Fetch movies from the database
$sql = "SELECT * FROM movies";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Movies | Netflix</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #141414;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 40px;
        }

        .movie-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .movie-item {
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .movie-item h2 {
            font-size: 1.5rem;
            margin: 0 0 10px;
        }

        .movie-item p {
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

        .btn-add-movie {
            display: inline-block;
            background-color: #e50914;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            border-radius: 4px;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .btn-add-movie:hover {
            background-color: #f40612;
        }

        hr {
            border: 1px solid #333;
        }

        .movie-description {
            font-size: 0.9rem;
            color: #b3b3b3;
        }

        .release-year {
            font-size: 0.85rem;
            color: #737373;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>All Movies</h1>

        <!-- If the user is logged in, show the "Add New Movie" link -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/movie_app/create.php" class="btn-add-movie">Add a New Movie</a>
        <?php else: ?>
            <p><a href="/movie_app/login.php">Login</a> to add a new movie.</p>
        <?php endif; ?>

        <div class="movie-list">
            <?php
            // Check if there are movies to display
            if ($result->num_rows > 0) {
                // Output data for each movie
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='movie-item'>";
                    // Display the movie title with a link to its slug page
                    echo "<h2><a href='/movie_app/movie.php?slug=" . $row['slug'] . "'>" . htmlspecialchars($row['title']) . "</a></h2>";
                    echo "<p class='movie-description'>" . htmlspecialchars($row['description']) . "</p>";
                    echo "<p class='release-year'>Release Year: " . htmlspecialchars($row['release_year']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "No movies found.";
            }

            // Close the database connection
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
