<?php
require_once __DIR__ . '/../data/db.php';
// define SQL query to fetch incident with customer, products and date, and tech info
$sql = "SELECT i.incidentID, c.firstName, c.lastName, i.productCode, p.name AS productName, i.dateOpened, i.title, i.description, 
               t.firstName AS techFirstName, t.lastName AS techLastName
        FROM incidents i
        JOIN customers c ON i.customerID = c.customerID
        JOIN products p ON i.productCode = p.productCode
        LEFT JOIN technicians t ON i.techID = t.techID
        ORDER BY i.dateOpened DESC";
// prepare and execute the query
$stmt = $db->prepare($sql);
$stmt->execute();
$incidents = $stmt->fetchAll(); // fetch all incidents into an array
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>All Incidents</title>
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

    <h1>SportsPro Technical Support</h1>
    <p>Sports management software for sports enthusiasts</p>
    <p><a href="../index.php" class="home-link">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none;">

    <h2 style="margin-top: 20px;">All Incidents</h2>
<!-- check if there are any incident to dispaly-->
    <?php if (count($incidents) > 0): ?>
        <table>
            <caption>All Incidents List</caption>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Incident</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidents as $incident): ?> <!-- loop through each incident -->
                <tr>
                    <!-- display customer name -->
                    <td><?= htmlspecialchars($incident['firstName'] . ' ' . $incident['lastName']) ?></td>
                    <!-- display products name -->
                    <td><?= htmlspecialchars($incident['productName']) ?></td>
                    <!-- display incident details -->
                    <td class="incident-details">
                        <div><strong>ID:</strong> <?= htmlspecialchars($incident['incidentID']) ?></div>
                        <div><strong>Opened:</strong> <?= htmlspecialchars($incident['dateOpened']) ?></div>
                        <div><strong>Title:</strong> <?= htmlspecialchars($incident['title']) ?></div>
                        <div><strong>Description:</strong> <?= nl2br(htmlspecialchars($incident['description'])) ?></div>
                       <!-- show tech name if assigned otherwise say unassigned -->
                        <?php if ($incident['techFirstName']): ?>
                            <div><strong>Technician:</strong> <?= htmlspecialchars($incident['techFirstName'] . ' ' . $incident['techLastName']) ?></div>
                        <?php else: ?>
                            <div><strong>Technician:</strong> <em>Unassigned</em></div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <!-- message shown if there are no incidents in the database -->
        <p class="no-results">No incidents found.</p>
    <?php endif; ?>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 40px; margin-bottom: 40px;">

    <footer style="text-align: right; font-size: 0.9em; color: #333;">
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>

</body>
</html>