<?php
include '../db_connection.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: auth.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM books WHERE status='Available'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #0f172a;
            color: #e2e8f0;
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background: #1e293b !important;
            border-bottom: 2px solid #3b82f6;
            padding: 1rem;
        }

        .navbar-brand, .nav-link {
            color: #ffffff !important;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: #3b82f6 !important;
        }

        .navbar-nav {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .search-bar {
            width: 200px;
            transition: all 0.3s ease-in-out;
        }

        .search-bar:hover, .search-bar:focus {
            width: 250px;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }

        .dashboard-link {
            margin-left: auto;
        }

        @media (max-width: 768px) {
            .navbar-nav {
                flex-direction: column;
                align-items: center;
                width: 100%;
            }

            .search-bar {
                width: 100%;
                margin-top: 10px;
            }

            .dashboard-link {
                margin-left: 0;
                text-align: center;
                width: 100%;
            }
        }

        .navbar-toggler {
            border: 2px solid #3b82f6 !important;
            background-color: #1e293b !important;
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }

        .container {
            padding: 2rem 1rem;
        }

        h2 {
            color: #3b82f6;
            font-weight: 700;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .card {
            background: #1e293b;
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.2);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            color: #60a5fa;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .card-text {
            color: #cbd5e1;
            font-size: 0.95rem;
        }

        .btn-success {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            border: none;
            padding: 0.6rem 1.8rem;
            border-radius: 50px;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(90deg, #60a5fa, #3b82f6);
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        /* Advanced Pop-up Styling */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #1e293b, #0f172a);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            color: #e2e8f0;
            animation: fadeIn 0.3s ease-in;
            display: none;
        }

        .popup-content {
            text-align: center;
        }

        .popup-content p {
            font-size: 22px;
            font-weight: 600;
            color: #60a5fa;
            margin-bottom: 20px;
            animation: slideUp 0.3s ease-out;
        }

        .popup-content button {
            padding: 12px 30px;
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            border: none;
            border-radius: 50px;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .popup-content button:hover {
            background: linear-gradient(90deg, #60a5fa, #3b82f6);
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">📚 Library</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <input type="text" id="search" class="form-control search-bar" placeholder="🔍 Search Books...">
                    </li>
                </ul>
                <ul class="navbar-nav dashboard-link">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">🏠 Dashboard</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>📚 Available Books</h2>
        <div class="row" id="book-list">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="col-md-4 col-sm-6 col-12 mb-4 book-item">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">📖 <?php echo $row['title']; ?></h5>
                            <p class="card-text">👨‍🏫 <strong>Author:</strong> <?php echo $row['author']; ?></p>
                            <p class="card-text">🆔 <strong>Book ID:</strong> <?php echo $row['book_id']; ?></p>
                            <form id="bookRequestForm_<?php echo $row['book_id']; ?>" class="d-inline" action="request_book.php" method="POST">
                                <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                <button type="submit" class="btn btn-success">📥 Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Advanced Pop-up -->
    <div id="successPopup" class="popup">
        <div class="popup-content">
            <p id="popupMessage"></p>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let books = document.querySelectorAll('.book-item');
            books.forEach(book => {
                let title = book.querySelector('.card-title').innerText.toLowerCase();
                book.style.display = title.includes(filter) ? 'block' : 'none';
            });
        });

        document.querySelectorAll('.btn-success').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const form = this.closest('form');
                const formData = new FormData(form);

                fetch('request_book.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('popupMessage').innerText = data === 'Book request submitted!' ? data : 'Error: ' + data;
                    document.getElementById('successPopup').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('popupMessage').innerText = 'An error occurred. Please try again.';
                    document.getElementById('successPopup').style.display = 'block';
                });
            });
        });

        function closePopup() {
            document.getElementById('successPopup').style.display = 'none';
        }
    </script>
</body>
</html>