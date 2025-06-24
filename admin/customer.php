<?php
session_start();
require_once __DIR__ . '/../data/db.php';
// Initialize variables for error messages and email input
$error = '';
$email = '';
// Check if the form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    // Validate that the entered value is a proper email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Prepare a SQL statement to find a customer with the given email
        $stmt = $db->prepare("SELECT * FROM customers WHERE email = :email");
        $stmt->bindValue(':email', $email); // Bind the email value to the SQL parameter
        $stmt->execute(); // execute the quert
        $customer = $stmt->fetch(); //fetch the customer

        if ($customer) {
            // If customer found, store their data in the session and redirect to register
            $_SESSION['customer'] = $customer;
            header("Location: register.php");
            exit; // Stop further script execution
        } else {             // If no matching customer found, show error message
            $error = "No customer found with that email.";
        }
    } else {         // Show error if the email format is invalid
        $error = "Please enter a valid email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Login</title>
</head>
<body style="background-color: #e0e7ff; margin: 0; padding: 0;">
<div style="max-width: 900px; margin: 40px auto; background-color: white; padding: 40px 50px; border: 1px solid #aaa; box-sizing: border-box;">

    <h1 style="margin-top: 0;">Sports Pro Technical Support</h1>
    <p style="margin-bottom: 5px;">Access your account to register products</p>
    <p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 20px;">

    <h2>Customer Login</h2>

    <?php if ($error): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" style="margin-top: 20px;">
        <label for="email"><strong>Email:</strong></label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>"
               style="width: 300px; padding: 8px; margin-top: 5px;" required><br><br>

        <button type="submit" style="padding: 10px 18px;">Login</button>
    </form>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 40px;">
    <footer style="text-align: right; font-size: 0.9em; color: #333;">
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>
</body>
</html>
