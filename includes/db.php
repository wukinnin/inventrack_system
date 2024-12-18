<?php
function getDBConnection() {
    $host = 'localhost';       // Database host
    $dbname = 'inventory_system'; // Your database name
    $username = 'root';        // MySQL username
    $password = '';            // MySQL password (leave blank for default XAMPP setup)
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>
<?php
$servername = "localhost"; // Change to your server if needed
$username = "root";        // Default XAMPP username
$password = "";            // Default XAMPP password (empty)
$dbname = "inventory_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
