<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

define('ADMIN_KEY', 'LIBRARY123');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $admin_key = $_POST['admin_key'];

    if ($admin_key !== ADMIN_KEY) {
        die("Invalid Admin Key. Signup not allowed.");
    }

    $query = "INSERT INTO Admins (username, password) VALUES ('$username', '$password')";
    
    if (mysqli_query($conn, $query)) {
        // ✅ Redirecting to `index.html` after successful signup
        header("Location: index.html");
        exit(); 
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>