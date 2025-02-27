<?php
include '../db_connection.php'; 
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: auth.php");
    exit();
}

if (!isset($_SESSION['student_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access!"]);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];
    $student_id = $_SESSION['student_id'];

    $check_transaction = "SELECT book_id, issue_date FROM transactions WHERE transaction_id = '$transaction_id' AND student_id = '$student_id' AND status = 'Issued'";
    $transaction_result = mysqli_query($conn, $check_transaction);

    if (!$transaction_result || mysqli_num_rows($transaction_result) == 0) {
        echo json_encode(["success" => false, "message" => "Invalid request or book already returned!"]);
        exit;
    }

    $transaction = mysqli_fetch_assoc($transaction_result);
    $book_id = $transaction['book_id'];
    $issue_date = $transaction['issue_date'];

    $due_date = date('Y-m-d', strtotime($issue_date . ' +7 days'));
    $return_date = date('Y-m-d');

    $fine = 0;
    if ($return_date > $due_date) {
        $days_late = (strtotime($return_date) - strtotime($due_date)) / (60 * 60 * 24);
        $fine = $days_late * 5; 
    }

    $update_transaction = "UPDATE transactions SET status = 'Returned', fine_amount = '$fine', return_date = '$return_date' WHERE transaction_id = '$transaction_id'";
    $update_book_status = "UPDATE books SET status = 'Available' WHERE book_id = '$book_id'";

    if (mysqli_query($conn, $update_transaction) && mysqli_query($conn, $update_book_status)) {
        echo json_encode(["success" => true, "message" => "Book returned successfully!", "fine" => $fine]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating records: " . mysqli_error($conn)]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request!"]);
}
?>