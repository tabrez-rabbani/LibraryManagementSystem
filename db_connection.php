<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_management";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// } else {
//     echo "Connected successfully"; // Temporary message to ensure connection is fine.
// }
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }
?>