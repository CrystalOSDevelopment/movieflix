<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "links_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$year = isset($_GET['year']) ? $_GET['year'] : '';
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : '';
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';

$Command = "";

if($orderBy != "") {
    switch($orderBy) {
        case '0':
            // Order by movie year in ascending order
            $orderBy = 'release_date ASC';
            break;
        case '1':
            // Order by movie year in descending order
            $orderBy = 'release_date DESC';
            break;
        case '2':
            // Order by movie title in ascending order
            $orderBy = 'release_date ASC';
            break;
        case '3':
            // Order by movie title in descending order
            $orderBy = 'release_date DESC';
            break;
        case '4':
            // Filter to only specific director (This is a TODO)
            $Command = "SELECT * FROM links WHERE DirANDActors LIKE ?";
            break;
        case '5':
            // Filter to only specific actor (This is a TODO)
            $Command = "SELECT * FROM links WHERE DirANDActors LIKE ?";
            break;
        case '6':
            // Filter to only specific genre (AKA Category)
            $Command = "SELECT * FROM links WHERE Category LIKE ?";
            break;
        case '7':
            // Order by rating in ascending order (This is a TODO, probably will be solved by joining tables)
            $Command = "SELECT * FROM links WHERE movie_title LIKE ? ORDER BY Rating ASC";
            break;
        case '8':
            // Order by rating in descending order (This is a TODO, probably will be solved by joining tables)
            $Command = "SELECT * FROM links WHERE movie_title LIKE ? ORDER BY Rating DESC";
            break;
        case '9':
            // Order by movie length in ascending order
            $orderBy = 'movie_length ASC';
            break;
        case '10':
            // Order by movie length in descending order
            $orderBy = 'movie_length DESC';
            break;
    }
}

if ($year != "") {
    $stmt = $conn->prepare("SELECT * FROM links WHERE release_date = ?");
    $stmt->bind_param("s", $year);
}
else if($Command != "") {
    $stmt = $conn->prepare($Command);
    $stmt->bind_param("s", $search);
}
else {
    $stmt = $conn->prepare("SELECT * FROM links WHERE movie_title LIKE ? " . ($orderBy != "" ? "ORDER BY " . $orderBy : ""));
    $stmt->bind_param("s", $search);
}

$stmt->execute();
$result = $stmt->get_result();
$movies = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($movies);

$stmt->close();
$conn->close();
?>