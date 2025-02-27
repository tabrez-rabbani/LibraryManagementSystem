<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login1.php"); // ✅ Redirect to index.html instead of login.php
    exit();
}
?>

<?php
include 'db_connection.php';

$query = "SELECT Transactions.transaction_id, Books.title, Students.name AS student_name, 
                 Transactions.issue_date, Transactions.fine_amount 
          FROM Transactions 
          JOIN Books ON Transactions.book_id = Books.book_id 
          JOIN Students ON Transactions.student_id = Students.student_id 
          WHERE Transactions.status = 'Issued'";

$result = mysqli_query($conn, $query);
?>

<h2 class="page-title">📚 Issued Books</h2>

<a href="admin_dashboard.php" class="back-link">⬅️ Back to Dashboard</a>

<table class="styled-table">
<tr>
    <th>Transaction ID</th>
    <th>Book Title</th>
    <th>Issued To</th>
    <th>Issue Date</th>
    <th>Fine (₹)</th>
    <th>Action</th>
</tr>

<?php
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr id='row_" . $row['transaction_id'] . "'>";
    echo "<td>" . $row['transaction_id'] . "</td>";
    echo "<td>" . $row['title'] . "</td>";
    echo "<td>" . $row['student_name'] . "</td>";
    echo "<td>" . $row['issue_date'] . "</td>";
    echo "<td>" . ($row['fine_amount'] > 0 ? "₹" . $row['fine_amount'] : "No Fine") . "</td>";
    echo "<td>
            <button class='return-btn' onclick='returnBook(" . $row['transaction_id'] . ")'>Return</button>
          </td>";
    echo "</tr>";
}
?>
</table>
<a href="generate_pdf.php" class="pdf-btn">📄 Download PDF Report</a>

<!-- SweetAlert & jQuery (For AJAX) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function returnBook(transactionId) {
    $.ajax({
        url: 'return_book.php',
        type: 'POST',
        data: { transaction_id: transactionId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: "Success!",
                    text: response.message + (response.fine > 0 ? " Fine: ₹" + response.fine : ""),
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(() => {
                    $("#row_" + transactionId).remove();
                });
            } else {
                Swal.fire({
                    title: "Error!",
                    text: response.message,
                    icon: "error",
                    confirmButtonText: "OK"
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: "Error!",
                text: "Something went wrong! Check console.",
                icon: "error",
                confirmButtonText: "OK"
            });
        }
    });
}
</script>

<style>
/* Centered Page Title */
.page-title {
    text-align: center;
    font-size: 24px;
    margin-top: 20px;
    color: #333;
}

/* Back to Dashboard Link */
.back-link {
    display: block;
    width: fit-content;
    margin: 10px auto;
    padding: 8px 15px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
}

.back-link:hover {
    background-color: #0056b3;
}

/* Stylish Table */
.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 18px;
    text-align: left;
}

.styled-table th, .styled-table td {
    padding: 12px;
    border-bottom: 3px solid #ddd;
}

.styled-table th {
    background-color: #007BFF;
    color: white;
}

/* Stylish Return Button */
.return-btn {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s;
}

.return-btn:hover {
    background-color: #218838;
}
.pdf-btn {
    display: inline-block;
    padding: 8px 15px;
    background-color: #FF5733;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
}

.pdf-btn:hover {
    background-color: #C70039;
}
</style>