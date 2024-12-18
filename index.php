<?php
// Include header and database connection
include './includes/db.php';

// Fetch summary data
$total_items = $conn->query("SELECT COUNT(*) AS count FROM items")->fetch_assoc()['count'];
$total_suppliers = $conn->query("SELECT COUNT(*) AS count FROM suppliers")->fetch_assoc()['count'];
$total_transactions = $conn->query("SELECT COUNT(*) AS count FROM transactions")->fetch_assoc()['count'];

// Fetch recent transactions
$recent_transactions = $conn->query("
    SELECT t.transaction_id, i.item_name, t.transaction_type, t.quantity, t.date
    FROM transactions t
    LEFT JOIN items i ON t.item_id = i.item_id
    ORDER BY t.date DESC LIMIT 5
");
?>

<!-- from header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvenTrack</title>
    <!-- Bootstrap CSS -->
    <link href="assets/bootstrap/bootstrap.css" rel="stylesheet">
    <!-- Custom CSS (Optional) -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">InvenTrack</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="views/items.php">Items</a></li>
                    <li class="nav-item"><a class="nav-link" href="views/suppliers.php">Suppliers</a></li>
                    <li class="nav-item"><a class="nav-link" href="views/transactions.php">Transactions</a></li>
                    <li class="nav-item"><a class="nav-link" href="reports/transactions_report.php">Reports</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">

<div class="container mt-4">
    <h1 class="mb-4">InvenTrack</h1>
    <p>An inventory management system that manages items for computers and electronics. 🚀</p>
    <p><a href="https://github.com/wukinnin/inventrack_system">Project on Github</a></p>
    <br>
    <h2 class="mb-4">Dashboard</h2>

    <div class="row mt-4">
        <div class="col-md-4">
            <a href="views/items.php" class="btn btn-primary btn-lg w-100">Manage Items</a>
        </div>
        <div class="col-md-4">
            <a href="views/suppliers.php" class="btn btn-success btn-lg w-100">Manage Suppliers</a>
        </div>
        <div class="col-md-4">
            <a href="views/transactions.php" class="btn btn-warning btn-lg w-100">Manage Transactions</a>
        </div>
    </div>
    <br>
    <!-- Summary cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Items</h5>
                    <p class="card-text fs-3"><?php echo $total_items; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Suppliers</h5>
                    <p class="card-text fs-3"><?php echo $total_suppliers; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Transactions</h5>
                    <p class="card-text fs-3"><?php echo $total_transactions; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent transactions table
    <div class="row">
        <div class="col-md-12">
            <h2>Recent Transactions</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Item Name</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $recent_transactions->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['transaction_id']; ?></td>
                        <td><?php echo $row['item_name']; ?></td>
                        <td><?php echo $row['transaction_type']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div> -->

   
    
</div>
