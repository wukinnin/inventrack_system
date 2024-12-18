<?php
// Include header and database connection
include '../includes/header.php';
include '../includes/db.php';

// Fetch all transactions
$query = "SELECT t.transaction_id, i.item_name, t.transaction_type, t.quantity, t.date 
          FROM transactions t
          LEFT JOIN items i ON t.item_id = i.item_id";
$result = $conn->query($query);

// Fetch all items for dropdown
$items = $conn->query("SELECT item_id, item_name FROM items");

// Handle form submission for adding a transaction
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_id = $_POST['item_id'];
    $transaction_type = $_POST['transaction_type'];
    $quantity = $_POST['quantity'];
    
    // Insert transaction
    $stmt = $conn->prepare("INSERT INTO transactions (item_id, transaction_type, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $item_id, $transaction_type, $quantity);
    $stmt->execute();

    // Update stock in items table
    $update_sql = $transaction_type === 'IN' ?
        "UPDATE items SET stock_quantity = stock_quantity + $quantity WHERE item_id=$item_id" :
        "UPDATE items SET stock_quantity = stock_quantity - $quantity WHERE item_id=$item_id";
    $conn->query($update_sql);

    header("Location: transactions.php"); // Refresh page after submission
    exit;
}

// Handle transaction deletion
if (isset($_GET['delete'])) {
    $transaction_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM transactions WHERE transaction_id=?");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    header("Location: transactions.php"); // Refresh page after deletion
    exit;
}
?>

<div class="row">
    <div class="col-md-8">
        <h2>Transactions</h2>
        <p>Track transactions of items heading in and out of the inventory. 🌟</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Item Name</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['transaction_id']; ?></td>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['transaction_type']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['transaction_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this transaction?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-4">
        <h2>Add Transaction</h2>
        <form action="transactions.php" method="POST">
            <div class="mb-3">
                <label for="item_id" class="form-label">Item</label>
                <select class="form-select" id="item_id" name="item_id" required>
                    <?php while ($item = $items->fetch_assoc()): ?>
                    <option value="<?php echo $item['item_id']; ?>"><?php echo $item['item_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="transaction_type" class="form-label">Transaction Type</label>
                <select class="form-select" id="transaction_type" name="transaction_type" required>
                    <option value="IN">IN</option>
                    <option value="OUT">OUT</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Transaction</button>
        </form>
    </div>
</div>