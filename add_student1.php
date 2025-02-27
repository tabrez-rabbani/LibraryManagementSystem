<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login1.php"); // ✅ Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #e2e8f0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 500px;
            width: 100%;
            padding: 2rem;
            background: rgba(30, 41, 59, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.5s ease-in;
        }
        h2 {
            color: #3b82f6;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            font-size: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
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
        .back-btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(90deg, #4CAF50, #45a049);
            text-decoration: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
            margin-top: 1rem;
            display: block;
            text-align: center;
        }
        .back-btn:hover {
            background: linear-gradient(90deg, #45a049, #4CAF50);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
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
            z-index: 1050;
            color: #e2e8f0;
            animation: fadeInPopup 0.3s ease-in;
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
        @keyframes fadeInPopup {
            from { opacity: 0; transform: translate(-50%, -60%); }
            to { opacity: 1; transform: translate(-50%, -50%); }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-user-plus"></i> Add New Student</h2>
        <form id="addStudentForm" action="add_student.php" method="POST">
            <div class="form-group">
                <label for="student_id" class="form-label">Student ID:</label>
                <input type="number" name="student_id" id="student_id" class="form-control" placeholder="Enter Student ID" required>
            </div>
            <div class="form-group">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Student Name" required>
            </div>
            <div class="form-group">
                <label for="department" class="form-label">Department:</label>
                <input type="text" name="department" id="department" class="form-control" placeholder="Enter Department" required>
            </div>
            <button type="submit">Add Student</button>
        </form>
        <a href="admin_dashboard.php" class="back-btn">🔙 Back to Dashboard</a>
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
        document.getElementById('addStudentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('add_student.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('popupMessage').innerText = data;
                document.getElementById('successPopup').style.display = 'block';
                
                // Optionally clear the form on success
                if (data.includes('successfully')) {
                    this.reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('popupMessage').innerText = 'An error occurred. Please try again.';
                document.getElementById('successPopup').style.display = 'block';
            });
        });

        function closePopup() {
            document.getElementById('successPopup').style.display = 'none';
        }
    </script>
</body>
</html>