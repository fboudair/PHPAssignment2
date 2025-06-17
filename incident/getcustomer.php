<?php
require_once __DIR__ . '/../data/db.php';

$email = '';
$error = '';
$customer = null;
// this page is sign in page for the customer using their email
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) { //query to find customer information usinf their email
        $stmt = $db->prepare('SELECT * FROM customers WHERE email = :email');
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $customer = $stmt->fetch();

        if ($customer) {
            // Redirect to create incident with customer ID
            header("Location: createIncident.php?customerID=" . $customer['customerID']);
            exit;
        } else {
            $error = "No customer found with that email.";
        }
    } else {
        $error = "Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Customer</title>
</head>
<body style="background-color: #e0e7ff; margin: 0; padding: 0;">
<div style="max-width: 900px; margin: 40px auto; background-color: white; padding: 40px 50px; border: 1px solid #aaa; box-sizing: border-box;">

    <h1 style="margin-top: 0;">Sports Pro Technical Support</h1>
    <p style="margin-bottom: 5px;">Sports management software for sports enthusiasts</p>
    <p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 20px;">

    <h2>Select Customer</h2>
    <p>You must enter a customer's email address to select the customer.</p>

    <?php if ($error): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

 <form action="" method="post" style="margin-top: 20px;">
    <label for="email" style="font-weight: bold;">Customer Email:</label><br>
    <div style="display: flex; gap: 10px; margin-top: 5px;">
        <input type="text" name="email" id="email" value="<?= htmlspecialchars($email) ?>" 
               style="width: 300px; padding: 8px;">
        <button type="submit" style="padding: 8px 16px;">Get Customer</button>
    </div>
</form>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 40px;">
    <footer style="text-align: right; font-size: 0.9em; color: #333;">
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>
</body>
</html>
