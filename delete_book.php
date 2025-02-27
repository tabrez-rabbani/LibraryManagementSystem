<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];

    // Pehle transactions delete karo
    $delete_transactions = "DELETE FROM transactions WHERE book_id = '$book_id'";
    mysqli_query($conn, $delete_transactions);

    // Ab book delete karo
    $delete_book = "DELETE FROM books WHERE book_id = '$book_id'";
    mysqli_query($conn, $delete_book);

    // Check karo ki books table empty ho gaya ya nahi
    $check_books = mysqli_query($conn, "SELECT COUNT(*) as total FROM books");
    $row = mysqli_fetch_assoc($check_books);
    
    if ($row['total'] == 0) {
        // Agar koi book nahi bacha to auto-increment reset karo
        mysqli_query($conn, "ALTER TABLE books AUTO_INCREMENT = 1");
    }

    echo "<script>
        window.onload = function() {
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                text: 'Book deleted successfully.',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.href='view_books.php';
            });
        };
    </script>";
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>