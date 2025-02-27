<?php
include '../db_connection.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: auth.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$result = mysqli_query($conn, "SELECT books.title, transactions.return_date, transactions.transaction_id 
                               FROM transactions 
                               JOIN books ON transactions.book_id = books.book_id 
                               WHERE transactions.student_id='$student_id' AND transactions.status='Issued'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Issued Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            padding: 2rem 1rem;
        }

        h2 {
            color: #60a5fa;
            font-weight: 700;
            font-size: 2rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 2.5rem;
            text-shadow: 0 2px 8px rgba(96, 165, 250, 0.3);
        }

        .book-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .book-card {
            background: #1e293b;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(96, 165, 250, 0.2);
        }

        .book-info {
            flex-grow: 1;
        }

        .book-title {
            color: #93c5fd;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .return-date {
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        .btn-return {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-return:hover {
            background: linear-gradient(90deg, #60a5fa, #3b82f6);
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .no-books {
            text-align: center;
            color: #94a3b8;
            font-size: 1.1rem;
            padding: 2rem;
            background: #1e293b;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 2rem;
            color: #60a5fa;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #93c5fd;
            text-shadow: 0 0 5px rgba(96, 165, 250, 0.5);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .book-card {
                flex-direction: column;
                text-align: center;
                padding: 1.25rem;
            }

            .btn-return {
                margin-top: 1rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            .book-title {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem 0.5rem;
            }

            .book-card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="book-container">
        <h2>Your Issued Books</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='book-card'>";
                echo "<div class='book-info'>";
                echo "<div class='book-title'>📖 {$row['title']}</div>";
                echo "<div class='return-date'>Return Date: {$row['return_date']}</div>";
                echo "</div>";
                echo "<button class='btn-return' onclick='returnBook({$row['transaction_id']})'>Return</button>";
                echo "</div>";
            }
        } else {
            echo "<div class='no-books'>No issued books.</div>";
        }
        ?>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <!-- SweetAlert2 Library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function returnBook(transactionId) {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you really want to return this book?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3b82f6",
                cancelButtonColor: "#ef4444",
                confirmButtonText: "Yes, Return it!",
                background: "#1e293b",
                color: "#e2e8f0",
                customClass: {
                    confirmButton: 'btn-return',
                    cancelButton: 'btn-return'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('return_book.php?transaction_id=' + transactionId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "Returned!",
                                    text: "Book returned successfully. Fine: ₹" + data.fine,
                                    icon: "success",
                                    background: "#1e293b",
                                    color: "#e2e8f0",
                                    confirmButtonColor: "#3b82f6"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: data.message,
                                    icon: "error",
                                    background: "#1e293b",
                                    color: "#e2e8f0",
                                    confirmButtonColor: "#3b82f6"
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: "Error!",
                                text: "Server connection failed!",
                                icon: "error",
                                background: "#1e293b",
                                color: "#e2e8f0",
                                confirmButtonColor: "#3b82f6"
                            });
                        });
                }
            });
        }
    </script>
</body>
</html>