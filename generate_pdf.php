<?php
require('fpdf.php'); // FPDF Library Include
include 'db_connection.php'; // Database Connection

// PDF Class Banaye
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 10, 'Issued Books Report', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Transaction ID', 1);
$pdf->Cell(60, 10, 'Book Title', 1);
$pdf->Cell(40, 10, 'Student Name', 1);
$pdf->Cell(40, 10, 'Issue Date', 1);
$pdf->Ln();

// Database Se Issued Books Fetch Karo
$query = "SELECT Transactions.transaction_id, Books.title, Students.name AS student_name, Transactions.issue_date 
          FROM Transactions 
          JOIN Books ON Transactions.book_id = Books.book_id 
          JOIN Students ON Transactions.student_id = Students.student_id 
          WHERE Transactions.status = 'Issued'";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, $row['transaction_id'], 1);
    $pdf->Cell(60, 10, $row['title'], 1);
    $pdf->Cell(40, 10, $row['student_name'], 1);
    $pdf->Cell(40, 10, $row['issue_date'], 1);
    $pdf->Ln();
}

$pdf->Output('D', 'Issued_Books_Report.pdf'); // PDF Download Karne Ke Liye
?>