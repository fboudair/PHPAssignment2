<?php
session_start();
require_once __DIR__ . '/../data/db.php';
// If either technician or incident is not selected, redirect back
if (!isset($_SESSION['selectedIncidentID'], $_SESSION['selectedTechID'])) {
    header("Location: assign.php"); 
    exit;
}
// Retrieve selected incident and technician IDs from session
$incidentID = $_SESSION['selectedIncidentID'];
$techID = $_SESSION['selectedTechID'];
// Fetch technician's full name
$stmt = $db->prepare("
    SELECT i.incidentID, i.productCode, 
           c.firstname AS customerFirst, c.lastname AS customerLast 
    FROM incidents i
    JOIN customers c ON i.customerID = c.customerID
    WHERE i.incidentID = :incidentID
");
$stmt->execute([':incidentID' => $incidentID]);
$incident = $stmt->fetch();

$stmt = $db->prepare("SELECT firstName, lastName FROM technicians WHERE techID = :techID");
$stmt->execute([':techID' => $techID]);
$tech = $stmt->fetch();
// Message shown after successful assignment
$message = '';
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign'])) {
    $stmt = $db->prepare("UPDATE incidents SET techID = :techID WHERE incidentID = :incidentID");
    $stmt->execute([':techID' => $techID, ':incidentID' => $incidentID]);
    // Success message
    $message = "Incident #$incidentID has been successfully assigned to {$tech['firstName']} {$tech['lastName']}.";
        // Clear session variables
    unset($_SESSION['selectedIncidentID'], $_SESSION['selectedTechID']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Confirm Incident Assignment</title>
    <style>
        body {
            background-color: #e0e7ff;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background-color: white;
            padding: 40px 50px;
            border: 1px solid #aaa;
            box-sizing: border-box;
        }

        button {
            padding: 10px 18px;
            margin-top: 20px;
        }

        a {
            color: #0000EE;
            text-decoration: underline;
        }

        a:hover {
            text-decoration: none;
        }

        footer {
            text-align: right;
            font-size: 0.9em;
            color: #333;
            margin-top: 40px;
        }

        p {
            margin: 10px 0;
        }

        .message {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>SportsPro Technical Support</h1>
    <p>Confirm incident assignment below.</p>
    <p><a href="../index.php"><strong>Home</strong></a></p>

    <hr style="height: 3px; background-color: black; border: none;">

    <h2>Confirm Assignment</h2>

    <?php if ($message): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
        <p><a href="assign.php">Select Another Incident</a></p>
    <?php else: ?>
        <p><strong>Customer:</strong> <?= htmlspecialchars($incident['customerFirst'] . ' ' . $incident['customerLast']) ?></p>
        <p><strong>Product Code:</strong> <?= htmlspecialchars($incident['productCode']) ?></p>
        <p><strong>Technician:</strong> <?= htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']) ?></p>

        <form method="post">
            <button type="submit" name="assign">Assign Incident</button>
        </form>

        <p><a href="assign.php">Cancel</a></p>
    <?php endif; ?>

    <hr style="height: 3px; background-color: black; border: none;">
    <footer>&copy; 2025 SportsPro, Inc.</footer>
</div>
</body>
</html>
