<?php
include '../db_connection.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: auth.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Library Hub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #60a5fa;
            --accent: #ef4444;
            --dark-bg: #0f172a;
            --card-bg: #1e293b;
            --text-light: #e2e8f0;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%; /* Ensure full height without extra */
            overflow-x: hidden; /* Prevent horizontal scroll */
            overflow-y: hidden; /* Temporarily disable vertical scroll to debug */
        }

        body {
            background: linear-gradient(145deg, var(--dark-bg) 0%, #1e293b 100%);
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            position: relative;
            margin: 0; /* Remove any default margin */
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%; /* Ensure it doesn't exceed viewport */
            height: 100%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
            z-index: -1;
            animation: pulse 15s infinite;
            contain: strict; /* Restrict overflow */
        }

        /* Navbar */
        .navbar {
            background: var(--card-bg);
            padding: 1.2rem 1rem; /* Reduced padding to stay within bounds */
            border-bottom: 3px solid var(--primary);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        .navbar-brand {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--secondary);
            text-shadow: 0 0 10px rgba(96, 165, 250, 0.6);
        }

        .nav-link {
            color: var(--text-light) !important;
            font-weight: 600;
            margin-left: 1.5rem;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary) !important;
            text-shadow: 0 0 5px rgba(59, 130, 246, 0.5);
        }

        .btn-logout {
            background: var(--accent);
            color: #fff !important;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #dc2626;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
        }

        /* Main Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1rem;
            width: 100%; /* Ensure it respects viewport */
            min-height: calc(100vh - 6rem); /* Adjust for navbar and margin */
            overflow-y: auto; /* Allow vertical scroll only if needed */
        }

        .welcome-text {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 3rem;
            text-shadow: 0 2px 10px rgba(59, 130, 246, 0.4);
            animation: fadeInDown 1s ease-in;
        }

        /* Card Grid */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            width: 100%;
            margin-bottom: 2rem; /* Ensure margin doesn't push content */
        }

        .dashboard-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.4s ease;
            cursor: pointer;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            transform: rotate(30deg);
            transition: all 0.5s ease;
            opacity: 0;
            z-index: 0;
        }

        .dashboard-card:hover::before {
            opacity: 1;
            transform: rotate(0deg);
        }

        .dashboard-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);
        }

        .card-icon {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .dashboard-card:hover .card-icon {
            transform: scale(1.1);
            text-shadow: 0 0 15px rgba(96, 165, 250, 0.6);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .card-text {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .card-btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .btn-view {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .btn-view:hover {
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.5);
            transform: scale(1.05);
        }

        .btn-issued {
            background: linear-gradient(90deg, #10b981, #34d399);
        }

        .btn-issued:hover {
            background: linear-gradient(90deg, #34d399, #10b981);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.5);
            transform: scale(1.05);
        }

        .btn-logout-card {
            background: linear-gradient(90deg, var(--accent), #f87171);
        }

        .btn-logout-card:hover {
            background: linear-gradient(90deg, #f87171, var(--accent));
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.5);
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .card-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            .welcome-text {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }

            .nav-link {
                margin-left: 0.5rem;
            }

            .dashboard-card {
                padding: 1.5rem;
            }

            .welcome-text {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-container {
                margin: 2rem auto;
                padding: 0 0.5rem;
            }

            .card-title {
                font-size: 1.25rem;
            }

            .card-icon {
                font-size: 2rem;
            }

            .card-btn {
                padding: 0.6rem 1.5rem;
            }

            .navbar-brand {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">📚 Library Hub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="books.php">Books</a></li>
                    <li class="nav-item"><a class="nav-link" href="issued_books.php">Issued</a></li>
                    <li class="nav-item"><a class="nav-link btn-logout" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard -->
    <div class="dashboard-container">
        <h1 class="welcome-text">Student Dashboard</h1>
        <div class="card-grid">
            <div class="dashboard-card" onclick="window.location.href='books.php'">
                <i class="fas fa-book card-icon"></i>
                <h5 class="card-title">View Books</h5>
                <p class="card-text">Browse all available books in the library collection.</p>
                <a href="books.php" class="card-btn btn-view">Explore Now</a>
            </div>
            <div class="dashboard-card" onclick="window.location.href='issued_books.php'">
                <i class="fas fa-list-check card-icon"></i>
                <h5 class="card-title">My Issued Books</h5>
                <p class="card-text">View and manage your currently issued books.</p>
                <a href="issued_books.php" class="card-btn btn-issued">Check Now</a>
            </div>
            <div class="dashboard-card" onclick="window.location.href='logout.php'">
                <i class="fas fa-sign-out-alt card-icon"></i>
                <h5 class="card-title">Logout</h5>
                <p class="card-text">Securely log out from your student account.</p>
                <a href="logout.php" class="card-btn btn-logout-card">Logout</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.dashboard-card').forEach(card => {
            card.addEventListener('click', function(e) {
                const link = this.querySelector('.card-btn');
                if (link && e.target !== link) {
                    window.location.href = link.href;
                }
            });
        });
    </script>
</body>
</html>