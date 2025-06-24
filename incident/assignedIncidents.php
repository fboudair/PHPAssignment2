<?php
require_once __DIR__ . '/../data/db.php';
// SQL: Fetch all incidents that are assigned to technicians
$sql = "SELECT i.incidentID, c.firstName, c.lastName, i.productCode, p.name AS productName, i.dateOpened, i.title, i.description, i.dateClosed,
               t.firstName AS techFirstName, t.lastName AS techLastName
        FROM incidents i
        JOIN customers c ON i.customerID = c.customerID
        JOIN products p ON i.productCode = p.productCode
        JOIN technicians t ON i.techID = t.techID
        ORDER BY i.dateOpened DESC";

$stmt = $db->prepare($sql);
$stmt->execute();
// Fetch all assigned incidents into array
$incidents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Assigned Incidents</title>
<style>
    body {
        background-color: #e0e7ff;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        color: #000;
    }
    .container {
        max-width: 900px;
        margin: 40px auto;
        background-color: white;
        padding: 40px 50px;
        border: 1px solid #aaa;
        box-sizing: border-box;
    }
    h1 {
        margin-top: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    caption {
        caption-side: top;
        font-weight: bold;
        margin-bottom: 10px;
        text-align: left;
    }
    td, th {
        border: 1px solid #aaa;
        padding: 8px;
        vertical-align: top;
    }
    th {
        background-color: #dbeafe;
    }
    .incident-details {
        line-height: 1.6;
    }
    .incident-details strong {
        display: inline-block;
        width: 90px;
    }
    .incident-details div {
        margin-bottom: 4px;
    }
    a.home-link {
        display: inline-block;
        margin-bottom: 20px;
        color: #0000EE;
        text-decoration: none;
        font-weight: bold;
    }
    a.home-link:hover {
        text-decoration: underline;
    }
    p.no-results {
        margin-top: 20px;
        font-style: italic;
    }
</style>
</head>
<body>

<div class="container">

    <h1>Assigned Incidents</h1>
    <p><a href="../index.php" class="home-link">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none;">

    <?php if (count($incidents) > 0): ?>
        <table>
            <caption>Assigned Incidents List</caption>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Incident</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidents as $incident): ?>
                <tr>
                    <td><?= htmlspecialchars($incident['firstName'] . ' ' . $incident['lastName']) ?></td>
                    <td><?= htmlspecialchars($incident['productName']) ?></td>
                    <td class="incident-details">
                        <div><strong>ID:</strong> <?= htmlspecialchars($incident['incidentID']) ?></div>
                        <div><strong>Opened:</strong> <?= htmlspecialchars(string: $incident['dateOpened']) ?></div>
<div><strong>Closed:</strong> <?= htmlspecialchars($incident['dateClosed'] ?? 'null') ?></div>

                        <div><strong>Title:</strong> <?= htmlspecialchars($incident['title']) ?></div>
                        <div><strong>Description:</strong> <?= nl2br(htmlspecialchars($incident['description'])) ?></div>
                        <div><strong>Technician:</strong> <?= htmlspecialchars($incident['techFirstName'] . ' ' . $incident['techLastName']) ?></div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
                <!-- Show message if no assigned incidents found -->
        <p class="no-results">No assigned incidents found.</p>
    <?php endif; ?>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 40px; margin-bottom: 40px;">

    <footer style="text-align: right; font-size: 0.9em; color: #333;">
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>

</body>
</html>
