<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dsn = 'mysql:host=127.0.0.1;port=3308;dbname=techsupport';
$username = 'root'; // XAMPP default
$password = '';     // XAMPP default (empty)

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit();
}
?>

