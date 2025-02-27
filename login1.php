<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management - Login</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
     <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="curved-shape"></div>
        <div class="form-box Login">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <div class="input-box">
                    <input type="text" name="username"
                    placeholder="Username" required>
                    <!-- <label for="">Username</label> -->
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password"
                    placeholder="Password" required>
                    <!-- <label for="">Password</label> -->
                    <i class='bx bx-lock-alt' ></i>
                </div>
                <div class="input-box">
                    <button class="btn" type="submit">Login</button>
                </div>
                
            </form>
        </div>
        <div class="info-content Login">
            <h2>Library Management System</h2>
            <p></p>
        </div>
    </div>

    <script src="index.js"></script>

    <!-- <div class="login-form">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div> -->
</body>
</html>