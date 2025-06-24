<?php
session_start();
require_once __DIR__ . '/../data/db.php';

$error = '';
$success = '';

$loggedInTechID = $_SESSION['techID'] ?? 1;
// Fetch all customers from the database
$customers = $db->query("SELECT customerID, firstname, lastname FROM customers")->fetchAll(PDO::FETCH_ASSOC);
// Fetch all products from the database
$products = $db->query("SELECT productCode, name FROM products")->fetchAll(PDO::FETCH_ASSOC);
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form input values
    $customerID = $_POST['customerID'] ?? '';
    $productCode = $_POST['productCode'] ?? '';
    $dateOpened = $_POST['dateOpened'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    // Validate that all fields are filled
    if ($customerID && $productCode && $dateOpened && $title && $description) {
        try {
                        // Prepare the SQL INSERT statement to insert a new incident
            $stmt = $db->prepare("INSERT INTO incidents (customerID, productCode, techID, dateOpened, title, description)
                                  VALUES (:customerID, :productCode, :techID, :dateOpened, :title, :description)");
                                              // Execute the statement with bound parameters
            $stmt->execute([
                ':customerID' => $customerID,
                ':productCode' => $productCode,
                ':techID' => $loggedInTechID,
                ':dateOpened' => $dateOpened,
                ':title' => $title,
                ':description' => $description
            ]);
                        // Set success message
            $success = "Incident report submitted.";
        } catch (PDOException $e) {
                        // If an error occurs, set error message
            $error = "Database error: " . $e->getMessage();
        }
    } else {
                // If any required field is missing
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Technician Incident Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0e7ff;
            margin: 0;
            padding: 0;
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

        label strong {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="datetime-local"],
        select,
        textarea {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button, input[type="submit"] {
            padding: 10px 18px;
            font-size: 14px;
            margin-top: 20px;
            cursor: pointer;
        }

        .message-success {
            color: green;
            font-weight: bold;
        }

        .message-error {
            color: red;
            font-weight: bold;
        }

        footer {
            text-align: right;
            font-size: 0.9em;
            color: #333;
            margin-top: 40px;
        }

        hr {
            height: 3px;
            background-color: black;
            border: none;
            margin: 40px 0;
        }

        a {
            color: #0000EE;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>SportsPro Technical Support</h1>
    <p>Submit a new incident report for a customer</p>
    <p><a href="../index.php"><strong>Home</strong></a></p>

    <hr>

    <h2>Technician Incident Report</h2>
    <!-- Display error message if exists -->
    <?php if ($error): ?>
        <p class="message-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <!-- Display success message if form was submitted -->
    <?php if ($success): ?>
        <p class="message-success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <!-- Incident Report Form -->
    <form method="post">
                <!-- Customer Dropdown -->
        <label><strong>Customer:</strong></label>
        <select name="customerID" required>
            <option value="">-- Select Customer --</option>
            <?php foreach ($customers as $c): ?>
                <option value="<?= $c['customerID'] ?>">
                    <?= htmlspecialchars($c['customerID'] . ' - ' . $c['firstname'] . ' ' . $c['lastname']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <!-- Product Dropdown -->
        <label><strong>Product:</strong></label>
        <select name="productCode" required>
            <option value="">-- Select Product --</option>
            <?php foreach ($products as $p): ?>
                <option value="<?= $p['productCode'] ?>">
                    <?= htmlspecialchars($p['productCode'] . ' - ' . $p['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <!-- Date field -->
        <label><strong>Date Opened:</strong></label>
        <input type="datetime-local" name="dateOpened" required>
        <!-- Title input -->
        <label><strong>Title:</strong></label>
        <input type="text" name="title" required>
        <!-- Description input -->
        <label><strong>Description:</strong></label>
        <textarea name="description" rows="5" required></textarea>
        <!-- Submit button -->
        <input type="submit" value="Submit Report">
    </form>

    <hr>
    <footer>
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>
</body>
</html>
