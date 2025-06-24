<?php
session_start();
require_once __DIR__ . '/../data/db.php';

if (!isset($_SESSION['technician'])) {
    header("Location: ../technician_login.php");
    exit();
}
// Get the currently logged-in technician's ID
$techID = $_SESSION['techID'];
// Handle logout request
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../technician_login.php");
    exit();
}
// Fetch incidents assigned to the current technician
$sql = "SELECT i.incidentID, c.firstname, c.lastname, i.productCode, i.dateOpened, i.title, i.description
        FROM incidents i
        JOIN customers c ON i.customerID = c.customerID
        WHERE i.techID = :techID
        ORDER BY i.dateOpened";
$stmt = $db->prepare($sql);
$stmt->execute([':techID' => $techID]);
$incidents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Technician Dashboard</title>
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
    header, footer {
        border-bottom: 3px solid black;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    footer {
        border-top: 3px solid black;
        border-bottom: none;
        padding-top: 10px;
        margin-top: 40px;
        text-align: right;
        font-size: 0.9em;
        color: #333;
    }
    h1 {
        margin: 0;
    }
    a.home-link {
        display: inline-block;
        margin-top: 5px;
        color: #0000EE;
        text-decoration: none;
        font-weight: bold;
    }
    a.home-link:hover {
        text-decoration: underline;
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
    button, input[type=submit] {
        padding: 6px 12px;
        cursor: pointer;
    }
    .signed-in-info {
        margin-top: 20px;
        font-weight: bold;
        font-style: italic;
    }
    form.logout-form {
        margin-top: 15px;
    }
</style>
</head>
<body>

<div class="container">

    <header>
        <h1>SportsPro Technical Support</h1>
        <a href="../index.php" class="home-link">Home</a>
    </header>

    <?php if (count($incidents) > 0): ?>
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
                        <form method="get" action="../incident/updateIncident.php" style="margin:0;">
                            <input type="hidden" name="id" value="<?= $incident['incidentID'] ?>">
                            <button type="submit">Select</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
                <!-- Show signed-in technician info and logout option -->
            <p class="signed-in-info">Signed in as Technician: <?= htmlspecialchars($_SESSION['technician']['firstName'] . ' ' . $_SESSION['technician']['lastName']) ?></p>

            <form method="post" class="logout-form">
                <input type="submit" name="logout" value="Logout">
            </form>
        <!-- Message if no incidents assigned -->
    <?php else: ?>
        <p>No incidents assigned to you.</p>
        <!-- Show signed-in technician info and logout option (again) -->
        <p class="signed-in-info">Signed in as Technician: <?= htmlspecialchars($_SESSION['technician']['firstName'] . ' ' . $_SESSION['technician']['lastName']) ?></p>

        <form method="post" class="logout-form">
            <input type="submit" name="logout" value="Logout">
        </form>
    <?php endif; ?>

    <footer>
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>

</body>
</html>
