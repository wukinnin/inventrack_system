<?php
// Include header and database connection
include '../includes/header.php';
include '../includes/db.php';

// Fetch all items
$query = "SELECT i.item_id, i.item_name, s.supplier_name, i.price, i.stock_quantity 
          FROM items i
          LEFT JOIN suppliers s ON i.supplier_id = s.supplier_id";
$result = $conn->query($query);

// Handle form submission for adding or updating an item
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $supplier_id = $_POST['supplier_id'] ?: null;
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    
    if (isset($_POST['item_id']) && $_POST['item_id']) {
        // Update existing item
        $item_id = $_POST['item_id'];
        $stmt = $conn->prepare("UPDATE items SET item_name=?, supplier_id=?, price=?, stock_quantity=? WHERE item_id=?");
        $stmt->bind_param("sidsi", $item_name, $supplier_id, $price, $stock_quantity, $item_id);
    } else {
        // Add new item
        $stmt = $conn->prepare("INSERT INTO items (item_name, supplier_id, price, stock_quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sids", $item_name, $supplier_id, $price, $stock_quantity);
    }
    $stmt->execute();
    header("Location: items.php"); // Refresh page after submission
    exit;
}

// Handle item deletion
if (isset($_GET['delete'])) {
    $item_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM items WHERE item_id=?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    header("Location: items.php"); // Refresh page after deletion
    exit;
}
?>

<div class="row">
    <div class="col-md-8">
        <h2>Items</h2>
        <p>Manage items of the variety of hardware components. 🌟</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Name</th>
                    <th>Supplier</th>
                    <th>Price</th>
                    <th>Stock Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['item_id']; ?></td>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['supplier_name'] ?: 'N/A'; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['stock_quantity']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['item_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?delete=<?php echo $row['item_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-4">
        <h2><?php echo isset($_GET['edit']) ? 'Edit Item' : 'Add Item'; ?></h2>
        <?php
        $edit_item = null;
        if (isset($_GET['edit'])) {
            $item_id = $_GET['edit'];
            $stmt = $conn->prepare("SELECT * FROM items WHERE item_id=?");
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $edit_item = $stmt->get_result()->fetch_assoc();
        }
        ?>
        <form action="items.php" method="POST">
            <input type="hidden" name="item_id" value="<?php echo $edit_item['item_id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" value="<?php echo $edit_item['item_name'] ?? ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="supplier_id" class="form-label">Supplier</label>
                <select class="form-select" id="supplier_id" name="supplier_id">
                    <option value="">Select Supplier</option>
                    <?php
                    $suppliers = $conn->query("SELECT supplier_id, supplier_name FROM suppliers");
                    while ($supplier = $suppliers->fetch_assoc()):
                    ?>
                    <option value="<?php echo $supplier['supplier_id']; ?>" 
                        <?php echo (isset($edit_item) && $edit_item['supplier_id'] == $supplier['supplier_id']) ? 'selected' : ''; ?>>
                        <?php echo $supplier['supplier_name']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $edit_item['price'] ?? ''; ?>" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" value="<?php echo $edit_item['stock_quantity'] ?? ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo isset($edit_item) ? 'Update Item' : 'Add Item'; ?></button>
        </form>
    </div>
</div>