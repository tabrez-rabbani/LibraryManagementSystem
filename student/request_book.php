<?php
include '../db_connection.php';
session_start();

// ✅ Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    die("You must be logged in to request a book.");
}

$student_id = $_SESSION['student_id'];

// ✅ Ensure book_id is present in the POST data
if (!isset($_POST['book_id']) || empty($_POST['book_id'])) {
    die("Invalid request! Book ID is missing.");
}

$book_id = $_POST['book_id'];

// ✅ Ensure book_id exists in books table
$check_book = mysqli_query($conn, "SELECT * FROM books WHERE book_id='$book_id'");
if (mysqli_num_rows($check_book) == 0) {
    die("Invalid book! This book does not exist.");
}

// ✅ Check if already requested
$check = mysqli_query($conn, "SELECT * FROM transactions WHERE book_id='$book_id' AND student_id='$student_id' AND status='Issued'");
if (mysqli_num_rows($check) > 0) {
    echo "You already have this book!";
} else {
    // ✅ Insert request into transactions table
    $query = "INSERT INTO transactions (book_id, student_id, issue_date, status) VALUES ('$book_id', '$student_id', NOW(), 'Requested')";
    if (mysqli_query($conn, $query)) {
        echo "Book request submitted!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>