<?php
include 'db_connection.php';
session_start();

// if (!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit();
// }

// Fetch pending book requests
$query = "SELECT transactions.transaction_id, students.name AS student_name, books.title AS book_title 
          FROM transactions 
          JOIN students ON transactions.student_id = students.student_id 
          JOIN books ON transactions.book_id = books.book_id 
          WHERE transactions.status = 'Requested'";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Book Requests</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #0f172a;
            color: #e2e8f0;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            padding: 2rem 1rem;
            max-width: 800px; /* Limiting container width for better centering */
        }
        h2 {
            color: #3b82f6;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
        }
        .request-item {
            background: #1e293b;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .request-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.2);
        }
        .request-details {
            flex: 1;
            padding-right: 1.5rem;
        }
        .request-details p {
            margin: 0;
            font-size: 1.1rem;
            line-height: 1.5;
        }
        .request-details strong {
            color: #60a5fa;
        }
        .button-group {
            display: flex;
            gap: 1rem;
            justify-content: center; /* Center-align buttons horizontally */
            align-items: center;
        }
        .btn-approve, .btn-reject {
            display: flex;
            justify-content: center; /* Center the text inside buttons */
            align-items: center;
            min-width: 120px; /* Ensure consistent button width */
        }
        .btn-approve {
            background: linear-gradient(90deg, #3b82f6, #60a5fa);
            color: #fff;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        .btn-approve:hover {
            background: linear-gradient(90deg, #60a5fa, #3b82f6);
            transform: scale(1.05);
        }
        .btn-reject {
            background: linear-gradient(90deg, #ef4444, #f87171);
            color: #fff;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        .btn-reject:hover {
            background: linear-gradient(90deg, #f87171, #ef4444);
            transform: scale(1.05);
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
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pending Book Requests</h2>
        <div id="request-list">
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="request-item" data-transaction-id="<?php echo $row['transaction_id']; ?>">
                        <div class="request-details">
                            <p><strong><?php echo $row['student_name']; ?></strong> requested '<strong><?php echo $row['book_title']; ?></strong>'</p>
                        </div>
                        <div class="button-group">
                            <form id="approveForm_<?php echo $row['transaction_id']; ?>" class="d-inline" action="approve_request.php" method="POST">
                                <input type="hidden" name="transaction_id" value="<?php echo $row['transaction_id']; ?>">
                                <button type="submit" class="btn-approve">✅ Approve</button>
                            </form>
                            <form id="rejectForm_<?php echo $row['transaction_id']; ?>" class="d-inline" action="reject_request.php" method="POST">
                                <input type="hidden" name="transaction_id" value="<?php echo $row['transaction_id']; ?>">
                                <button type="submit" class="btn-reject">❌ Reject</button>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No pending requests.</p>
            <?php } ?>
        </div>
        <a href="admin_dashboard.php" class="btn btn-secondary mt-3">🔙 Back to Dashboard</a>
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
        document.querySelectorAll('.btn-approve, .btn-reject').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const form = this.closest('form');
                const formData = new FormData(form);
                const transactionId = form.querySelector('input[name="transaction_id"]').value;
                const requestItem = document.querySelector(`.request-item[data-transaction-id="${transactionId}"]`);

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('popupMessage').innerText = data;
                    document.getElementById('successPopup').style.display = 'block';
                    
                    // Remove the request item from the DOM after successful action
                    if (data.includes('successfully')) {
                        requestItem.remove();
                        // Check if the list is empty and show "No pending requests" message
                        const requestList = document.getElementById('request-list');
                        if (requestList.querySelectorAll('.request-item').length === 0) {
                            requestList.innerHTML = '<p>No pending requests.</p>';
                        }
                    }
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