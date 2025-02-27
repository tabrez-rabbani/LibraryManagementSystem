<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM Admins WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Password verify karega hashed password se
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header('Location: admin_dashboard.php'); // ✅ Redirect to PHP dashboard
            exit();
        } else {
            echo "<script>alert('Invalid credentials!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid credentials!'); window.location.href='login.php';</script>";
    }
}
?>