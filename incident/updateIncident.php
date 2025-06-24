<?php
session_start();
require_once __DIR__ . '/../data/db.php';

if (!isset($_SESSION['technician'])) {
    header("Location: ../technician_login.php");
    exit();
}
// Get the incident ID from the URL
$incidentID = isset($_GET['id']) ? intval($_GET['id']) : 0;
$successMessage = '';
$error = '';
// If no valid incident ID is provided
if ($incidentID === 0) {
    die("Invalid incident ID.");
}

$stmt = $db->prepare('SELECT * FROM incidents WHERE incidentID = :id');
$stmt->execute([':id' => $incidentID]);
$incident = $stmt->fetch(PDO::FETCH_ASSOC);
// If no incident found
if (!$incident) {
    die("Incident not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');
    $dateClosed = trim($_POST['dateClosed'] ?? '');

    if ($description) {
                // Update the incident's description and (optional) dateClosed
        $stmt = $db->prepare('
            UPDATE incidents
            SET description = :description,
                dateClosed = :dateClosed
            WHERE incidentID = :id
        ');
        $stmt->execute([
            ':description' => $description,
            ':dateClosed' => $dateClosed !== '' ? $dateClosed : null,
            ':id' => $incidentID
        ]);
        $successMessage = "This incident was updated.";
    } else {
        $error = "Description is required.";
    }
}


if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../technician_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Incident #<?= htmlspecialchars($incidentID) ?></title>
    <style>
        body {
            background-color: #e0e7ff;
            margin: 0; padding: 0;
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
            margin-bottom: 30px;
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
        input[type=text], textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            font-size: 1em;
        }
        label strong {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        button, input[type=submit] {
            margin-top: 20px;
            padding: 10px 18px;
            cursor: pointer;
            font-size: 1em;
        }
        .message-success {
            color: green;
            font-weight: bold;
        }
        .message-error {
            color: red;
            font-weight: bold;
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
        .signed-in-info {
            margin-top: 30px;
            font-style: italic;
            font-weight: bold;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        .info-row {
            margin-bottom: 8px;
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
        <p><a href="../index.php" class="home-link">Home</a></p>
    </header>
    <!-- Show success message if update occurred -->
    <?php if ($successMessage): ?>
        <p class="message-success"><?= htmlspecialchars($successMessage) ?></p>
        <p><a href="../technician/assign.php">Select another incident</a></p>
    <?php else: ?>
                <!-- Show error message if form is incomplete -->
        <?php if ($error): ?>
            <p class="message-error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <!-- Incident edit form -->
        <form method="post">
            <div class="info-row"><strong>Incident ID:</strong> <?= htmlspecialchars($incident['incidentID'] ?? '') ?></div>
            <div class="info-row"><strong>Customer ID:</strong> <?= htmlspecialchars($incident['customerID'] ?? '') ?></div>
            <div class="info-row"><strong>Product Code:</strong> <?= htmlspecialchars($incident['productCode'] ?? '') ?></div>
            <div class="info-row"><strong>Date Opened:</strong> <?= htmlspecialchars($incident['dateOpened'] ?? '') ?></div>
              <label for="dateClosed"><strong>Date Closed (YYYY-MM-DD):</strong></label>
            <input type="text" id="dateClosed" name="dateClosed" value="<?= htmlspecialchars($incident['dateClosed'] ?? '') ?>">

            <div class="info-row"><strong>Title:</strong> <?= htmlspecialchars($incident['title'] ?? '') ?></div>

            <label for="description"><strong>Description:</strong></label>
            <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($incident['description'] ?? '') ?></textarea>

         
            <button type="submit">Update Incident</button>
        </form>
    <?php endif; ?>
    <!-- Display signed-in technician and logout option -->
    <div class="signed-in-info clearfix">
        <p>Signed in as Technician: <?= htmlspecialchars($_SESSION['technician']['firstName'] . ' ' . $_SESSION['technician']['lastName']) ?></p>
        <form method="post" class="logout-form">
            <input type="submit" name="logout" value="Logout">
        </form>
    </div>

    <footer>
        &copy; 2025 SportsPro, Inc.
    </footer>

</div>

</body>
</html>