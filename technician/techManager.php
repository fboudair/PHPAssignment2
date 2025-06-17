<?php
require_once __DIR__ . '/../data/db.php';

$query = $db->query('SELECT * FROM technicians ORDER BY lastName');
$technicians = $query->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Technician Management</title>
</head>
<body style="background-color: #e0e7ff; margin: 0; padding: 0;">
<div style="max-width: 1100px; min-height: 600px; margin: 40px auto; background-color: white; padding: 40px 50px; border: 1px solid #aaa; box-sizing: border-box;">

    <h1 style="margin-top: 20px;">SportsPro Technical Support</h1>
    <p>Sports management software for sports enthusiasts</p>
    <p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none;">

    <h2 style="margin-top: 20px;">Technicians</h2>
    <p><a href="addTechnician.php" style="font-weight: bold;">Add New Technician</a></p>

    <table cellpadding="8" border="1" style="border-collapse: collapse; width: 100%;">
        <tr style="background-color: #dbeafe;">
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($technicians as $tech): ?>
        <tr>
            <td><?= htmlspecialchars($tech['techID']) ?></td>
            <td><?= htmlspecialchars($tech['firstName']) ?></td>
            <td><?= htmlspecialchars($tech['lastName']) ?></td>
            <td><?= htmlspecialchars($tech['email']) ?></td>
            <td><?= htmlspecialchars($tech['password']) ?></td>
            <td><?= htmlspecialchars($tech['phone']) ?></td>
            <td>
                <a href="editTechnician.php?techID=<?= urlencode($tech['techID']) ?>">Edit</a> |
                <a href="deleteTechnician.php?techID=<?= urlencode($tech['techID']) ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <hr style="height: 3px; background-color: black; border: none;">
    <footer style="text-align: right; font-size: 0.9em; color: #333;">
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>
</body>
</html>
