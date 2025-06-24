<?php
require_once __DIR__ . '/../data/db.php';
// SQL query: Get all incidents where no technician is assigned (techID IS NULL)
$sql = "SELECT i.incidentID, c.firstName, c.lastName, i.productCode, p.name AS productName, i.dateOpened, i.title, i.description
        FROM incidents i
        JOIN customers c ON i.customerID = c.customerID
        JOIN products p ON i.productCode = p.productCode
        WHERE i.techID IS NULL
        ORDER BY i.dateOpened DESC";

$stmt = $db->prepare($sql);
$stmt->execute();
// Fetch all unassigned incidents
$incidents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Unassigned Incidents</title>
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

    <h1>Unassigned Incidents</h1>
    <p><a href="../index.php" class="home-link">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none;">

    <?php if (count($incidents) > 0): ?>
        <table>
            <caption>Unassigned Incidents List</caption>
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
                        <div><strong>Opened:</strong> <?= htmlspecialchars($incident['dateOpened']) ?></div>
                        <div><strong>Title:</strong> <?= htmlspecialchars($incident['title']) ?></div>
                        <div><strong>Description:</strong> <?= nl2br(htmlspecialchars($incident['description'])) ?></div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-results">No unassigned incidents found.</p>
    <?php endif; ?>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 40px; margin-bottom: 40px;">

    <footer style="text-align: right; font-size: 0.9em; color: #333;">
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>

</body>
</html>