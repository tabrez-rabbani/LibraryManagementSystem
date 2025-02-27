<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login1.php"); // ✅ Redirect to login if not logged in
    exit();
}
?>

<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];

    $query = "INSERT INTO Books (title, author, category) VALUES ('$title', '$author', '$category')";
    if (mysqli_query($conn, $query)) {
        echo "Book added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    exit;
}
?>