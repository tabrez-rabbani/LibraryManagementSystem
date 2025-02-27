<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: login1.php"); // Agar login nahi hai toh wapas bhejo
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid Request!");
}

$student_id = $_GET['id'];

// Student details fetch karo
$query = "SELECT * FROM students WHERE student_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    die("Student not found!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $department = $_POST['department'];

    // Update Query
    $updateQuery = "UPDATE students SET name = ?, department = ? WHERE student_id = ?";
    $updateStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, "ssi", $name, $department, $student_id);

    if (mysqli_stmt_execute($updateStmt)) {
        $_SESSION['success_message'] = "Student details updated successfully!";
        header("Location: view_members.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Failed to update student details!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        form { width: 50%; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        label, input, select { display: block; width: 100%; margin-bottom: 10px; }
        .submit-btn { background: #28a745; color: white; padding: 10px; border: none; cursor: pointer; }
        .submit-btn:hover { background: #218838; }

        body {
        font-family: Arial, sans-serif;
        text-align: center;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 50%;
        margin: 50px auto;
        background: white;
        padding: 20px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    h2 {
        color: #007bff;
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-top: 15px;
        text-align: left;
    }

    input[type="text"] {
        width: calc(100% - 20px);
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 16px;
        display: block;
        margin-bottom: 10px;
    }

    .submit-btn {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        border-radius: 5px;
        width: 100%;
        margin-top: 10px;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }

    .back-btn {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 15px;
        background-color: #dc3545;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }

    .back-btn:hover {
        background-color: #a71d2a;
    }

    </style>
</head>
<body>
<div class="container">
    <h2>Edit Student Details</h2>
    <form method="POST">
        <label for="student_id">Student ID:</label>
        <input type="text" name="student_id" value="<?= htmlspecialchars($student['student_id']) ?>" readonly>

        <label for="name">Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>

        <label for="department">Department:</label>
        <input type="text" name="department" value="<?= htmlspecialchars($student['department']) ?>" required>

        <button type="submit" class="submit-btn">Update</button>
    </form>
    
    <a href="view_members.php" class="back-btn">⬅ Back to Members</a>
</div>
</body>
</html>
