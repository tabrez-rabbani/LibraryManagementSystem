<?php
include 'db_connection.php';
session_start(); // Start session for alert message

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Step 1: Transactions delete karo
    $deleteTransactions = "DELETE FROM transactions WHERE student_id = '$id'";
    mysqli_query($conn, $deleteTransactions);

    // Step 2: Student delete karo
    $deleteStudent = "DELETE FROM students WHERE student_id = '$id'";

    if (mysqli_query($conn, $deleteStudent)) {
        $_SESSION['success_message'] = "Student deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Error deleting student!";
    }

    // Redirect to view_members.php
    header("Location: view_members.php");
    exit();
}
?>