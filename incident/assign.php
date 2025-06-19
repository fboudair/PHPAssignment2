<?php
session_start();
require_once __DIR__ . '/../data/db.php';  

$sql = "SELECT i.incidentID, c.firstname, c.lastname, i.productCode, i.dateOpened, i.title, i.description
        FROM incidents i
        JOIN customers c ON i.customerID = c.customerID
        WHERE i.techID IS NULL
        ORDER BY i.dateOpened";

$stmt = $db->prepare($sql);
$stmt->execute();
$incidents = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['incidentID'])) {
    $_SESSION['selectedIncidentID'] = $_POST['incidentID'];
    header("Location: select.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Select Incident</title>
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
        margin-top: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    table, th, td {
        border: 1px solid #aaa;
    }
    th, td {
        padding: 8px;
        text-align: left;
        vertical-align: top;
    }
  
 
    p.no-incidents {
        margin-top: 20px;
        font-style: italic;
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
</style>
</head>
<body>

<div class="container">

   <h1 style="margin-top: 20px;">SportsPro Technical Support</h1>
<p>Sports management software for sports enthusiasts</p>
<p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>

<hr style="height: 3px; background-color: black; border: none;">
<h2 style="margin-top: 20px;">Select Technician</h2>


    <?php if (count($incidents) > 0): ?>
    <form method="post" action="">
        <table>
            <thead>
 <tr style="background-color: #dbeafe;">
                        <th>Customer</th>
                    <th>Product</th>
                    <th>Date Opened</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incidents as $incident): ?>
                <tr>
                    <td><?= htmlspecialchars($incident['firstname'] . ' ' . $incident['lastname']) ?></td>
                    <td><?= htmlspecialchars($incident['productCode']) ?></td>
                    <td><?= htmlspecialchars($incident['dateOpened']) ?></td>
                    <td><?= htmlspecialchars($incident['title']) ?></td>
                    <td><?= htmlspecialchars($incident['description']) ?></td>
                    <td>
                        <button type="submit" name="incidentID" value="<?= htmlspecialchars($incident['incidentID']) ?>">
                            Select
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
    <?php else: ?>
        <p class="no-incidents">No unassigned incidents found.</p>
    <?php endif; ?>
<hr style="height: 3px; background-color: black; border: none; margin-top: 40px; margin-bottom: 40px;">


<footer style="text-align: right; font-size: 0.9em; color: #333;">
  &copy; 2025 SportsPro, Inc.
</footer>
</div>
</body>
</html>