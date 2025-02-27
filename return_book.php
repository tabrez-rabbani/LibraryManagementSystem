<?php
include 'db_connection.php';
header('Content-Type: application/json'); // JSON response ensure karein

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaction_id = $_POST['transaction_id'];

    // Get book issue date
    $get_transaction_query = "SELECT book_id, issue_date FROM Transactions WHERE transaction_id = '$transaction_id'";
    $transaction_result = mysqli_query($conn, $get_transaction_query);

    if (!$transaction_result) {
        echo json_encode(["success" => false, "message" => "Database error: " . mysqli_error($conn)]);
        exit;
    }

    $transaction = mysqli_fetch_assoc($transaction_result);
    $book_id = $transaction['book_id'];
    $issue_date = $transaction['issue_date'];

    // Calculate due date (7 days after issue date)
    $due_date = date('Y-m-d', strtotime($issue_date . ' +7 days'));
    $return_date = date('Y-m-d'); // Aaj ka date

    // Fine Calculation
    $fine = 0;
    if ($return_date > $due_date) {
        $days_late = (strtotime($return_date) - strtotime($due_date)) / (60 * 60 * 24);
        $fine = $days_late * 5; // ₹5 per day late fine
    }

    // Update transaction status & fine amount
    $update_transaction = "UPDATE Transactions SET status = 'Returned', fine_amount = '$fine' WHERE transaction_id = '$transaction_id'";
    $update_book_status = "UPDATE Books SET status = 'Available' WHERE book_id = '$book_id'";

    if (mysqli_query($conn, $update_transaction) && mysqli_query($conn, $update_book_status)) {
        echo json_encode(["success" => true, "message" => "Book returned successfully!", "fine" => $fine]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating records: " . mysqli_error($conn)]);
    }
}
?>