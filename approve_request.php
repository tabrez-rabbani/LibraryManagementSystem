<?php
include 'db_connection.php';
session_start();

// if (!isset($_SESSION['admin_id'])) {
//     die("You must be logged in as admin.");
// }

if (isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];

    // Fetch book_id from transaction
    $book_query = "SELECT book_id FROM transactions WHERE transaction_id='$transaction_id'";
    $book_result = mysqli_query($conn, $book_query);
    $book_row = mysqli_fetch_assoc($book_result);
    $book_id = $book_row['book_id'];

    // Update status to "Issued" in transactions table & set return date
    $return_date = date('Y-m-d', strtotime('+7 days'));
    $update_transaction = "UPDATE transactions SET status='Issued', return_date='$return_date' WHERE transaction_id='$transaction_id'";

    // Update book status to "Issued" in books table
    $update_book = "UPDATE books SET status='Issued' WHERE book_id='$book_id'";

    if (mysqli_query($conn, $update_transaction) && mysqli_query($conn, $update_book)) {
        echo "Book request approved successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request! Transaction ID is missing.";
}
?>