<?php
include 'db_connection.php';
session_start();

// if (!isset($_SESSION['admin_id'])) {
//     die("You must be logged in as admin.");
// }

if (isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];

    // Delete the book request from transactions
    $query = "DELETE FROM transactions WHERE transaction_id = '$transaction_id'";

    if (mysqli_query($conn, $query)) {
        echo "Book request rejected successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request! Transaction ID is missing.";
}
?>