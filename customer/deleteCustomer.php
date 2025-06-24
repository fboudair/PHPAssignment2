<?php
require_once __DIR__ . '/../data/db.php';
// Get the customer ID from the query string (GET parameter), or null if not set
$customerID = $_GET['customerID'] ?? null;
// If no customer ID was provided, stop execution and show error
if (!$customerID) {
    die('Customer ID not provided.');
}

try {
        // Prepare the DELETE SQL statement using a parameterized query to prevent SQL injection
    $stmt = $db->prepare('DELETE FROM customers WHERE customerID = ?');
    $stmt->execute([$customerID]);
    header('Location: customerManagement.php');
    exit;
        // If an error occurs during deletion, display the error message
} catch (PDOException $e) {
    die("Error deleting customer: " . $e->getMessage());
}
