<?php
require_once '../vendor/autoload.php'; // TCPDF Composer Autoloader
require_once '../includes/db.php';     // Database connection

// Function to fetch transaction data
function fetchTransactionsData() {
    $conn = getDBConnection(); // PDO Connection
    $query = "SELECT t.transaction_id, i.item_name, t.transaction_type, t.quantity, t.date
              FROM transactions t
              JOIN items i ON t.item_id = i.item_id
              ORDER BY t.date DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch data as associative array
}

// Fetch data
$data = fetchTransactionsData();

// Initialize PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Inventory System');
$pdf->SetTitle('Stock Transactions Report');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// PDF Header
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Stock Transactions Report', 0, 1, 'C');
$pdf->Ln(5);

// Table Header
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(20, 10, 'ID', 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'Item Name', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Type', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', 1);
$pdf->Cell(50, 10, 'Date', 1, 1, 'C', 1);

// Table Content
$pdf->SetFont('helvetica', '', 10);
foreach ($data as $row) {
    $pdf->Cell(20, 10, $row['transaction_id'], 1);
    $pdf->Cell(50, 10, $row['item_name'], 1);
    $pdf->Cell(30, 10, $row['transaction_type'], 1);
    $pdf->Cell(30, 10, $row['quantity'], 1, 0, 'R');
    $pdf->Cell(50, 10, $row['date'], 1, 1);
}

// Output PDF
ob_clean(); // Clear previous output to avoid TCPDF errors
$pdf->Output('transactions_report.pdf', 'I');
?>
