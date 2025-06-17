<?php
require_once __DIR__ . '/../data/db.php';
$techID = $_GET['techID'];

$stmt = $db->prepare('DELETE FROM technicians WHERE techID = ?');
$stmt->execute([$techID]);

header('Location: techManager.php');
exit();
?>

