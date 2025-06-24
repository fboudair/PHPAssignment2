<?php
session_start();
require_once __DIR__ . '/../data/db.php';

if (!isset($_SESSION['customer'])) {
    header("Location: customerLogin.php");
    exit;
}

$customer = $_SESSION['customer'];
$registrationMessage = '';
$registrationError = '';
// Fetch all products from the database to display in dropdown
$query = $db->query('SELECT * FROM products ORDER BY name');
$products = $query->fetchAll();
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productCode = $_POST['productCode'] ?? '';
    // Get selected product code
    if ($productCode) {
                // Check if this product is already registered by the customer
        $check = $db->prepare("SELECT * FROM registrations WHERE customerID = :cid AND productCode = :pc");
        $check->execute([':cid' => $customer['customerID'], ':pc' => $productCode]);

        if ($check->fetch()) {
                    // Check if this product is already registered by the customer
            $registrationError = "This product is already registered.";
        } else {
                        // Register product for the customer
            $stmt = $db->prepare("INSERT INTO registrations (customerID, productCode, registrationDate)
                                  VALUES (:cid, :pc, CURDATE())");
            if ($stmt->execute([':cid' => $customer['customerID'], ':pc' => $productCode])) {
                $registrationMessage = "Product registered successfully!";
            } else {
                $registrationError = "Failed to register product.";
            }
        }
    } else {
                // If no product was selected
        $registrationError = "Please select a product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Product</title>
</head>
<body style="background-color: #e0e7ff; margin: 0; padding: 0;">
<div style="max-width: 900px; margin: 40px auto; background-color: white; padding: 40px 50px; border: 1px solid #aaa; box-sizing: border-box;">

    <h1 style="margin-top: 0;">Sports Pro Technical Support</h1>
    <p style="margin-bottom: 5px;">Product registration portal for customers</p>
    <p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 20px;">

    <h2>Register a Product</h2>

    <?php if ($registrationMessage): ?>
        <p style="color: green; font-weight: bold;"><?= htmlspecialchars($registrationMessage) ?></p>
    <?php elseif ($registrationError): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($registrationError) ?></p>
    <?php endif; ?>

    <form method="post" style="margin-top: 20px;">
    <label style="font-weight: bold; display: inline-block; width: 100px;">Customer:</label>
    <span style="font-weight: normal; margin-left: 15px;">
        <?= htmlspecialchars($customer['firstname'] . ' ' . $customer['lastname']) ?>
    </span>
    <br><br>

    <label for="productCode" style="font-weight: bold; display: inline-block; width: 100px;">Product:</label>
    <select name="productCode" id="productCode" style="width: 300px; padding: 8px; margin-left: 15px;" required>
        <option value="">-- Choose --</option>
        <?php foreach ($products as $p): ?>
            <option value="<?= htmlspecialchars($p['productCode']) ?>">
                <?= htmlspecialchars($p['name']) ?> (v<?= htmlspecialchars($p['version']) ?>)
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit" style="padding: 10px 18px; margin-left: 115px;">Register Product</button>
</form>


  <p>You are logged in as <strong><?= htmlspecialchars($customer['email']) ?></strong></p>

<form action="logout.php" method="post" style="margin-top: 10px;">
    <button type="submit" style="padding: 8px 16px;">Logout</button>
</form>


    <hr style="height: 3px; background-color: black; border: none; margin-top: 40px;">
    <footer style="text-align: right; font-size: 0.9em; color: #333;">
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>
</body>
</html>
