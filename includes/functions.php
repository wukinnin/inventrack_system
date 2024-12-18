<?php
// Include database connection
include 'db.php';

/**
 * Fetch all records from a table
 * @param string $table - Table name
 * @return array - Resulting rows as an associative array
 */
function getAllRecords($table) {
    global $conn;
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

/**
 * Insert a new record into a table
 * @param string $table - Table name
 * @param array $data - Associative array of column => value
 * @return bool - True if successful, false otherwise
 */
function insertRecord($table, $data) {
    global $conn;
    $columns = implode(", ", array_keys($data));
    $values = implode("', '", array_values($data));
    $sql = "INSERT INTO $table ($columns) VALUES ('$values')";

    return $conn->query($sql);
}

/**
 * Update a record in a table
 * @param string $table - Table name
 * @param array $data - Associative array of column => value
 * @param string $condition - WHERE clause (e.g., "id = 1")
 * @return bool - True if successful, false otherwise
 */
function updateRecord($table, $data, $condition) {
    global $conn;
    $updates = [];
    foreach ($data as $column => $value) {
        $updates[] = "$column = '$value'";
    }
    $updatesStr = implode(", ", $updates);
    $sql = "UPDATE $table SET $updatesStr WHERE $condition";

    return $conn->query($sql);
}

/**
 * Delete a record from a table
 * @param string $table - Table name
 * @param string $condition - WHERE clause (e.g., "id = 1")
 * @return bool - True if successful, false otherwise
 */
function deleteRecord($table, $condition) {
    global $conn;
    $sql = "DELETE FROM $table WHERE $condition";

    return $conn->query($sql);
}
?>
