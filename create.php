<?php
// Include the database connection
include 'db.php';

// Initialize variables to store form data and error messages
$title = $description = $slug = $release_year = "";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
    $release_year = isset($_POST['release_year']) ? trim($_POST['release_year']) : '';

    // Validate the form inputs
    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    }
    if (empty($slug)) {
        $errors[] = "Slug is required.";
    }
    if (empty($release_year) || !is_numeric($release_year)) {
        $errors[] = "Valid release year is required.";
    }

    // If no errors, proceed to insert the movie into the database
    if (empty($errors)) {
        $sql = "INSERT INTO movies (title, description, slug, release_year) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $title, $description, $slug, $release_year);

        if ($stmt->execute()) {
            // Redirect to the movie list after successful insertion
            header("Location: /movie_app/movies.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a New Movie</title>
    <style>
        body {
            background-color: #141414;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #181818;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            color: #e50914;
        }

        label {
            font-size: 1rem;
            color: #b3b3b3;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
            background-color: #333;
            color: #fff;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #e50914;
            border: none;
            color: white;
            font-size: 1.1rem;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #f40612;
        }

        .error-list {
            background-color: #ff4d4d;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-list li {
            color: white;
            list-style-type: none;
        }

        .back-link {
            display: block;
            text-align: center;
            color: #e50914;
            margin-top: 20px;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add a New Movie</h1>

        <!-- Display errors if any -->
        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Movie creation form -->
        <form action="create.php" method="POST">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea><br>

            <label for="slug">Slug:</label><br>
            <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($slug); ?>" required><br>

            <label for="release_year">Release Year:</label><br>
            <input type="text" id="release_year" name="release_year" value="<?php echo htmlspecialchars($release_year); ?>" required><br>

            <input type="submit" value="Add Movie">
        </form>

        <a class="back-link" href="/movie_app/movies.php">Back to Movies</a>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
