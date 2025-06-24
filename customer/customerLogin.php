<?php
session_start();
// Include the database connection
require_once __DIR__ . '/../data/db.php';
// Initialize variables
$error = '';
$email = '';
// Check if the request method is POST (i.e., the form was submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize user inputs
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    // Validate email and password
    if ($email && $password && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Prepare and execute query to find user by email
        $stmt = $db->prepare("SELECT * FROM customers WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        // Verify the password using hashed password from the database
        if ($customer && password_verify($password, $customer['password'])) {
                        // Login successful – store user info in session
            $_SESSION['customer'] = $customer;
                        // Redirect to the registration page
            header("Location: register.php");
            exit;
        } else {
                        // Redirect to the registration page
            $error = "Invalid email or password.";
        }
    } else {
                // Missing or invalid input
        $error = "Please enter a valid email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Login</title>
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

        input, button {
            padding: 8px;
            font-size: 14px;
        }

        button {
            padding: 10px 18px;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        footer {
            text-align: right;
            font-size: 0.9em;
            color: #333;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>SportsPro Technical Support</h1>
    <p>Access your account to register products</p>
    <p><a href="../index.php">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none;">

    <h2>Customer Login</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <div style="margin-bottom: 15px;">
            <label for="email"><strong>Email:</strong></label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required style="width: 300px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="password"><strong>Password:</strong></label><br>
            <input type="password" id="password" name="password" required style="width: 300px;">
        </div>

        <button type="submit">Login</button>
    </form>

    <hr style="height: 3px; background-color: black; border: none;">
    <footer>&copy; 2025 SportsPro, Inc.</footer>
</div>
</body>
</html>
