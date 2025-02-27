<?php
include '../db_connection.php'; // Database connection
session_start();

// Login Logic
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM students WHERE username='$username'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['student_username'] = $row['username'];
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            $login_error = "Incorrect password!";
        }
    } else {
        $login_error = "User not found!";
    }
}

// Register Logic
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_user = mysqli_query($conn, "SELECT * FROM students WHERE username='$username'");
    if (mysqli_num_rows($check_user) > 0) {
        $register_error = "Username already taken!";
    } else {
        $query = "INSERT INTO students (name, email, username, password) VALUES ('$name', '$email', '$username', '$password')";
        if (mysqli_query($conn, $query)) {
            $register_success = "Registration successful! You can now log in.";
        } else {
            $register_error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0a1a2e, #162d4a);
            color: #e2e8f0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
        }
        .auth-container {
            max-width: 500px;
            width: 100%;
            padding: 2.5rem;
            background: rgba(15, 23, 42, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.5), 0 0 20px rgba(59, 130, 246, 0.3);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(59, 130, 246, 0.2);
            animation: fadeIn 0.5s ease-in;
            position: relative;
        }
        h2 {
            color: #3b82f6;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            text-transform: uppercase;
            font-size: 2rem;
            text-shadow: 0 2px 10px rgba(59, 130, 246, 0.4);
        }
        .form-box {
            display: none;
        }
        .form-box.active {
            display: block;
        }
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .form-label {
            font-weight: 600;
            color: #60a5fa;
            margin-bottom: 0.5rem;
            display: block;
        }
        .form-control {
            background: #1e293b;
            border: 2px solid #3b82f6;
            color: #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 10px rgba(96, 165, 250, 0.5);
            outline: none;
        }
        .form-control::placeholder {
            color: #94a3b8;
        }
        button[type="submit"] {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            color: #ffffff;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        button[type="submit"]:hover {
            background: linear-gradient(90deg, #60a5fa, #3b82f6);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
        }
        .toggle-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .toggle-link a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .toggle-link a:hover {
            color: #3b82f6;
        }
        .error, .success {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 1rem;
        }
        .error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            text-shadow: 0 1px 3px rgba(239, 68, 68, 0.2);
        }
        .success {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
            text-shadow: 0 1px 3px rgba(34, 197, 94, 0.2);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            h2 {
                font-size: 1.5rem;
            }
            .form-control {
                padding: 0.6rem 0.9rem;
                font-size: 0.9rem;
            }
            button[type="submit"] {
                padding: 0.6rem 1.5rem;
                font-size: 1rem;
            }
        }
        @media (max-width: 480px) {
            .auth-container {
                padding: 1.5rem 1rem;
                margin: 0.5rem;
            }
            h2 {
                font-size: 1.2rem;
            }
            .form-control {
                padding: 0.5rem 0.8rem;
                font-size: 0.8rem;
            }
            button[type="submit"] {
                padding: 0.5rem 1.2rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Login Form -->
        <div class="form-box <?php echo (!isset($_POST['register']) || isset($login_error)) ? 'active' : ''; ?>" id="login">
            <h2><i class="fas fa-sign-in-alt"></i> Login</h2>
            <?php if (isset($login_error)) { echo "<p class='error'>$login_error</p>"; } ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
            <div class="toggle-link">
                <p>Don't have an account? <a href="#" onclick="toggleForm('register')">Sign Up</a></p>
            </div>
        </div>

        <!-- Register Form -->
        <div class="form-box <?php echo (isset($_POST['register']) && !isset($register_error)) || isset($register_error) ? 'active' : ''; ?>" id="register">
            <h2><i class="fas fa-user-plus"></i> Sign Up</h2>
            <?php 
                if (isset($register_error)) { echo "<p class='error'>$register_error</p>"; }
                if (isset($register_success)) { echo "<p class='success'>$register_success</p>"; }
            ?>
            <form method="POST">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" name="register">Register</button>
            </form>
            <div class="toggle-link">
                <p>Already have an account? <a href="#" onclick="toggleForm('login')">Login</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleForm(formId) {
            document.getElementById('login').classList.remove('active');
            document.getElementById('register').classList.remove('active');
            document.getElementById(formId).classList.add('active');
        }
    </script>
</body>
</html>