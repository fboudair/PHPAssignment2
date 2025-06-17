<?php
require_once __DIR__ . '/../data/db.php';

$customerID = $_GET['customerID'] ?? null;

if (!$customerID) {
    die('Customer ID not provided.');
}

try {
    $stmt = $db->prepare('DELETE FROM customers WHERE customerID = ?');
    $stmt->execute([$customerID]);
    header('Location: customerManagement.php');
    exit;
} catch (PDOException $e) {
    die("Error deleting customer: " . $e->getMessage());
}
