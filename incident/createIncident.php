<?php
require_once __DIR__ . '/../data/db.php'; // getting the customer idea from database 

$customerID = isset($_GET['customerID']) ? intval($_GET['customerID']) : 0;
$successMessage = '';
$error = '';
$title = '';
$description = '';
$selectedProduct = '';
//if customer idea not found finish this page
if ($customerID === 0) {
    die("Invalid customer ID.");
}
//getting the customer details from the data base
$stmt = $db->prepare('SELECT * FROM customers WHERE customerID = :id');
$stmt->bindValue(':id', $customerID);
$stmt->execute();
$customer = $stmt->fetch();

if (!$customer) {
    die("Customer not found.");
}
//getting all products submit it by the current customer 
$stmt = $db->prepare('
    SELECT p.productCode, p.name 
    FROM products p 
    INNER JOIN registrations r ON p.productCode = r.productCode 
    WHERE r.customerID = :customerID
');
$stmt->bindValue(':customerID', $customerID);
$stmt->execute();
$products = $stmt->fetchAll();
//creating a form to get information of incident 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedProduct = $_POST['productCode'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
//check if all the field have data
    if ($selectedProduct && $title && $description) {
        $stmtTech = $db->query('SELECT techID FROM technicians ORDER BY RAND() LIMIT 1');
        $randomTech = $stmtTech->fetch();
        $randomTechID = $randomTech ? $randomTech['techID'] : null;

        if ($randomTechID === null) {
            $error = "No technician available to assign.";
        } else { // insert new row in the incident table
            $stmt = $db->prepare('
                INSERT INTO incidents (customerID, productCode, title, description, dateOpened, dateClosed, techID)
                VALUES (:customerID, :productCode, :title, :description, NOW(), NULL, :techID)
            ');
            $stmt->execute([
                ':customerID' => $customerID,
                ':productCode' => $selectedProduct,
                ':title' => $title,
                ':description' => $description,
                ':techID' => $randomTechID
            ]);
// if the row was add it create this message
            $successMessage = "Incident successfully added.";
            $title = '';
            $description = '';
            $selectedProduct = '';
        }
    } else {
        $error = "All fields are required.";
    }
}

// Fetch incidents for this customer, including customerID and description
$stmt = $db->prepare('
    SELECT incidentID, customerID, productCode, title, description, techID, dateOpened, dateClosed
    FROM incidents
    WHERE customerID = :customerID
    ORDER BY dateOpened DESC
');
$stmt->execute([':customerID' => $customerID]);
$incidents = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add New Incident</title>
    <style>
        body {
            background-color: #e0e7ff;
            margin: 0; padding: 0;
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
        }
        th {
            background-color: #f0f0f0;
        }
        a {
            color: #0000EE;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        label strong {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        input[type=text], select, textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        input[readonly] {
            background-color: #f0f0f0;
        }
        button {
            margin-top: 20px;
            padding: 10px 18px;
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
        hr {
            height: 3px;
            background-color: black;
            border: none;
            margin-top: 40px;
            margin-bottom: 40px;
        }
        footer {
            text-align: right;
            font-size: 0.9em;
            color: #333;
        }
    </style>
</head>
<body>
<div class="container">

    <h1 style="margin-top: 0;">Sports Pro Technical Support</h1>
    <p>Creating incident for: <strong><?= htmlspecialchars($customer['firstname'] . ' ' . $customer['lastname']) ?></strong></p>
    <p><a href="../index.php"><strong>Home</strong></a></p>

    <hr>

    <h2>Create Incident</h2>
    <!--display the sucess message -->
    <?php if ($successMessage): ?>
        <p class="message-success"><?= htmlspecialchars($successMessage) ?></p>
    <?php elseif ($error): ?>
        <p class="message-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (count($products) === 0): ?>
        <p>This customer has not registered any products.</p>     <!-- creaating a table to show a table of incident  to related to this customer -->
    <?php else: ?>
        <form action="" method="post">
            <label><strong>Customer ID:</strong></label>
            <input type="text" value="<?= htmlspecialchars($customerID) ?>" readonly>

            <label for="productCode"><strong>Product:</strong></label>
            <select name="productCode" id="productCode" required>
                <option value="">-- Select a product --</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?= htmlspecialchars($product['productCode']) ?>" <?= $selectedProduct == $product['productCode'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($product['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
    <!-- display the date of incident was create it -->

            <label><strong>Date Opened:</strong></label>
            <input type="text" value="<?= date('Y-m-d H:i:s') ?>" readonly>
    <!-- display the date of incident was close -->

            <label><strong>Date Closed:</strong></label>
            <input type="text" value="" readonly>
    <!-- display the title of incident -->

            <label for="title"><strong>Title:</strong></label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($title) ?>" required>
    <!-- display the description -->

            <label for="description"><strong>Description:</strong></label>
            <textarea name="description" id="description" rows="5" required><?= htmlspecialchars($description) ?></textarea>
    <!-- a button to create the incident -->

            <button type="submit">Create Incident</button>
        </form>
    <?php endif; ?>

    <h3 style="margin-top: 40px;">Previous Incidents</h3>
    <!-- da table that choose previous incident creating that customer -->

    <?php if (count($incidents) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Incident ID</th>
                    <th>Customer ID</th>
                    <th>Product</th>
                    <th>Tech ID</th>
                    <th>Date Opened</th>
                    <th>Date Closed</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($incidents as $incident): ?>
                <tr>
                    <td><?= htmlspecialchars($incident['incidentID']) ?></td>
                    <td><?= htmlspecialchars($incident['customerID']) ?></td>
                    <td><?= htmlspecialchars($incident['productCode']) ?></td>
                    <td><?= htmlspecialchars($incident['techID']) ?></td>
                    <td><?= htmlspecialchars($incident['dateOpened']) ?></td>
                    <td><?= htmlspecialchars($incident['dateClosed'] ?? '') ?></td>
                    <td><?= htmlspecialchars($incident['title']) ?></td>
                    <td><?= htmlspecialchars($incident['description']) ?></td>
                    <td>
                        <a href="editIncident.php?id=<?= urlencode($incident['incidentID']) ?>">Edit</a> |
                        <a href="deleteIncident.php?id=<?= urlencode($incident['incidentID']) ?>" onclick="return confirm('Are you sure you want to delete this incident?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No incidents recorded for this customer yet.</p>
    <?php endif; ?>

    <hr>
    <footer>
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>
</body>
</html>
