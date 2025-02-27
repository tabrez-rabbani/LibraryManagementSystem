<?php
include 'db_connection.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login1.php"); // ✅ Redirect to index.html
    exit();
}

// Pagination settings
$limit = 10; // Books per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search Query Handling
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM Books WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR category LIKE '%$search%' LIMIT $limit OFFSET $offset";
    $countQuery = "SELECT COUNT(*) as total FROM Books WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR category LIKE '%$search%'";
} else {
    $query = "SELECT * FROM Books LIMIT $limit OFFSET $offset";
    $countQuery = "SELECT COUNT(*) as total FROM Books";
}
$result = mysqli_query($conn, $query);
$totalResult = mysqli_fetch_assoc(mysqli_query($conn, $countQuery));
$totalBooks = $totalResult['total'];
$totalPages = ceil($totalBooks / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>View All Books</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .search-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-input {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-btn {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            background: #4CAF50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .table-container {
            width: 90%;
            margin: auto;
            overflow-x: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        tr:hover {
            background: #ddd;
            transition: 0.3s;
        }
        .pagination {
            text-align: center;
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 15px;
            text-decoration: none;
            color: white;
            background: #4CAF50;
            margin: 0 5px;
            border-radius: 5px;
        }
        .pagination a:hover {
            background: #45a049;
        }
        .div-btn {
            text-align: center;
            margin-top: 20px;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>All Books</h2>

<!-- Search Bar -->
<div class="search-container">
    <form method="GET">
        <input type="text" name="search" class="search-input" placeholder="Search by Title, Author, or Category..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="search-btn">Search</button>
    </form>
</div>

<div class="table-container">
<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Author</th>
        <th>Category</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php if (mysqli_num_rows($result) > 0) { ?>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['book_id']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['author']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td>
                    <?php echo ($row['status'] == 'Approved') ? 'Issued' : $row['status']; ?>
                </td>
                <td>
                    <form action="delete_book.php" method="POST" onsubmit="return confirmDelete(event, this)">
                        <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                        <button type="submit" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="6" style="text-align: center; padding: 10px; color: red;">No books found!</td>
        </tr>
    <?php } ?>
</table>
</div>

<!-- Pagination Links -->
<div class="pagination">
    <?php if ($page > 1) { ?>
        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
    <?php } ?>
    <?php if ($page < $totalPages) { ?>
        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
    <?php } ?>
</div>

<script>
function confirmDelete(event, form) {
    event.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>

<div class="div-btn">
    <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>