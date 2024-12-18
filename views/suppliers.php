<?php
// Include header and database connection
include '../includes/header.php';
include '../includes/db.php';

// Fetch all suppliers
$query = "SELECT * FROM suppliers";
$result = $conn->query($query);

// Handle form submission for adding or updating a supplier
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier_name = $_POST['supplier_name'];
    $contact = $_POST['contact'];
    
    if (isset($_POST['supplier_id']) && $_POST['supplier_id']) {
        // Update existing supplier
        $supplier_id = $_POST['supplier_id'];
        $stmt = $conn->prepare("UPDATE suppliers SET supplier_name=?, contact=? WHERE supplier_id=?");
        $stmt->bind_param("ssi", $supplier_name, $contact, $supplier_id);
    } else {
        // Add new supplier
        $stmt = $conn->prepare("INSERT INTO suppliers (supplier_name, contact) VALUES (?, ?)");
        $stmt->bind_param("ss", $supplier_name, $contact);
    }
    $stmt->execute();
    header("Location: suppliers.php"); // Refresh page after submission
    exit;
}

// Handle supplier deletion
if (isset($_GET['delete'])) {
    $supplier_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM suppliers WHERE supplier_id=?");
    $stmt->bind_param("i", $supplier_id);
    $stmt->execute();
    header("Location: suppliers.php"); // Refresh page after deletion
    exit;
}
?>

<div class="row">
    <div class="col-md-8">
        <h2>Suppliers</h2>
        <p>Identify suppliers and the items they entail. 🌟</p>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Supplier ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['supplier_id']; ?></td>
                    <td><?php echo $row['supplier_name']; ?></td>
                    <td><?php echo $row['contact']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['supplier_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="?delete=<?php echo $row['supplier_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this supplier?');">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-4">
        <h2><?php echo isset($_GET['edit']) ? 'Edit Supplier' : 'Add Supplier'; ?></h2>
        <?php
        $edit_supplier = null;
        if (isset($_GET['edit'])) {
            $supplier_id = $_GET['edit'];
            $stmt = $conn->prepare("SELECT * FROM suppliers WHERE supplier_id=?");
            $stmt->bind_param("i", $supplier_id);
            $stmt->execute();
            $edit_supplier = $stmt->get_result()->fetch_assoc();
        }
        ?>
        <form action="suppliers.php" method="POST">
            <input type="hidden" name="supplier_id" value="<?php echo $edit_supplier['supplier_id'] ?? ''; ?>">
            <div class="mb-3">
                <label for="supplier_name" class="form-label">Supplier Name</label>
                <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="<?php echo $edit_supplier['supplier_name'] ?? ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $edit_supplier['contact'] ?? ''; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo isset($edit_supplier) ? 'Update Supplier' : 'Add Supplier'; ?></button>
        </form>
    </div>
</div>