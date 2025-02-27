<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login1.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #e2e8f0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .dashboard-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 1rem;
        }
        .admin-header {
            background: linear-gradient(90deg, #1e293b, #0f172a);
            padding: 1.5rem 2rem;
            border-radius: 15px 15px 0 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            margin-bottom: 1.5rem;
            animation: fadeIn 0.5s ease-in;
            display: flex;
            justify-content: space-between; /* This will push logout to the right */
            align-items: center;
        }
        .admin-header h1 {
            color: #3b82f6;
            font-weight: 700;
            font-size: 2rem;
            margin: 0;
        }
        .logout-btn {
            background: linear-gradient(90deg, #ef4444, #f87171);
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .logout-btn:hover {
            background: linear-gradient(90deg, #f87171, #ef4444);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.6);
        }
        .admin-nav {
            background: rgba(30, 41, 59, 0.95);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 1.5rem;
            animation: slideIn 0.5s ease-in;
        }
        .admin-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .admin-nav a {
            color: #e2e8f0;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(to right, rgba(59, 130, 246, 0.1), rgba(30, 41, 59, 0.1));
        }
        .admin-nav a:hover {
            background: linear-gradient(to right, rgba(59, 130, 246, 0.3), rgba(30, 41, 59, 0.3));
            color: #60a5fa;
            transform: translateX(5px);
            box-shadow: 0 2px 10px rgba(59, 130, 246, 0.2);
        }
        .admin-nav a i {
            font-size: 1.2rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-header {
                padding: 1rem;
            }
            .admin-header h1 {
                font-size: 1.5rem;
            }
            .logout-btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            .admin-nav {
                padding: 1rem;
            }
            .admin-nav a {
                font-size: 1rem;
                padding: 0.5rem 1rem;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="admin-header">
            <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
        </header>

        <nav class="admin-nav">
            <ul>
                <li><a href="add_book1.php"><i class="fas fa-plus"></i> Add New Book</a></li>
                <li><a href="issue_book1.php"><i class="fas fa-book"></i> Issue Book</a></li>
                <li><a href="view_books.php"><i class="fas fa-book-open"></i> View All Books</a></li>
                <li><a href="view_issued_books.php"><i class="fas fa-book-reader"></i> 📚 View Issued Books</a></li>
                <li><a href="add_student1.php"><i class="fas fa-user-plus"></i> Add New Student</a></li>
                <li><a href="view_members.php"><i class="fas fa-users"></i> View All Students</a></li>
                <li><a href="book_requests.php"><i class="fas fa-clock"></i> Pending Book Requests</a></li>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>