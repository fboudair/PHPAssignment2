<?php
session_start();
require_once __DIR__ . '/data/db.php'; 
// Variables to store login feedback messages
$loginError = '';
$loginSuccess = '';
// If the customer is already logged in, set a success message
if (isset($_SESSION['customer'])) {
    $loginSuccess = "Logged in as " . htmlspecialchars($_SESSION['customer']['firstname']);
}
// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Prepare and execute a query to find the customer by email
    $stmt = $db->prepare("SELECT * FROM customers WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $customer = $stmt->fetch();
    // If customer found and password matches, log them in
    if ($customer && password_verify($password, $customer['password'])) {
        $_SESSION['customer'] = $customer;
        $loginSuccess = "Logged in as " . htmlspecialchars($customer['firstname']);
        $loginError = ''; // clear error if any
    } else {
                // If login fails
        $loginError = "Invalid email or password.";
        $loginSuccess = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SportsPro Technical Support</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0e7ff;
            margin: 0;
            padding: 0;
        }

        .container {
            background: #fff;
            max-width: 800px;
            margin: 60px auto;
            padding: 40px 50px;
            border: 1px solid #aaa;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h1 {
            margin-top: 0;
            color: #333;
        }

        .tagline {
            font-style: italic;
            margin-bottom: 20px;
        }

        a {
            color: #0000EE;
            text-decoration: underline;
            font-weight: bold;
        }

        hr {
            height: 3px;
            background-color: black;
            border: none;
            margin: 20px 0;
        }

        .login-links {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-section {
            margin: 30px 0;
            text-align: center;
        }

        .login-section h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .login-section a {
            display: block;
            margin: 8px 0;
            font-size: 16px;
        }

        footer {
            text-align: right;
            font-size: 0.9em;
            color: #333;
            margin: 30px 50px 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SportsPro Technical Support</h1>
        <p class="tagline">Sports management software for the sports enthusiast</p>
        <a href="../index.php">Home</a>
        <div style="text-align: right;">
            <?php if (!empty($loginSuccess)): ?>
                <p style="color: green; font-weight: bold;"><?= $loginSuccess ?></p>

                <form method="post" action="logout.php" style="margin-top: 10px;">
                    <button type="submit" style="padding: 8px 16px;">Logout</button>
                </form>
            <?php else: ?>
                <?php if (!empty($loginError)): ?>
                    <p style="color: red; font-weight: bold;"><?= htmlspecialchars($loginError) ?></p>
                <?php endif; ?>

                <form method="post" action="" style="text-align: right;">
                    <div style="margin-bottom: 12px; display: flex; justify-content: flex-end; align-items: center;">
                        <label for="email" style="width: 80px; font-weight: bold; text-align: left; margin-right: 10px;">Email:</label>
                        <input type="email" id="email" name="email" required style="width: 300px; padding: 8px;">
                    </div>

                    <div style="margin-bottom: 15px; display: flex; justify-content: flex-end; align-items: center;">
                        <label for="password" style="width: 80px; font-weight: bold; text-align: left; margin-right: 10px;">Password:</label>
                        <input type="password" id="password" name="password" required style="width: 300px; padding: 8px;">
                    </div>

                    <button type="submit" style="padding: 8px 16px;">Log In</button>
                </form>
            <?php endif; ?>
        </div>

        <hr>
<!-- Administrator dashboard links: Provides navigation to manage products, technicians, and incidents based on user session status -->
        <div class="login-links">
            <div class="login-section">
                <h1>Administrators</h1>
                <a href="admin/index.php">Manage Products</a>
                <?php if (isset($_SESSION['customer'])): ?>
                    <a href="customer/register.php">Register Products</a>
                <?php else: ?>
                    <a href="customer/customerlogin.php">Register Products</a>
                <?php endif; ?>
                <a href="technician/techManager.php">Manage Technicians</a>
                <a href="incident/getcustomer.php">Create Incident</a>
                <a href="incident/assign.php">Assign Incident</a>
                <a href="incident/display.php">Display Incidents</a>
                <a href="technician/login.php">Update Incidents</a>
                <a href="incident/assignedIncidents.php">assign Incidents</a>
                <a href="incident/unassignedIncidents.php">unassign Incidents</a>

            </div>

            <div class="login-section">
                <h1>Technicians</h1>
                <a href="technician/techManager.php">Manage Technicians</a>
                <a href="admin/login.php">Update Incidents</a>
                <a href="technician/report.php">report Incidents</a>
            </div>

            <div class="login-section">
                <h1>Customers</h1>
                <a href="customer/customerManagement.php">Manage Customers</a>
                <a href="customer/addcustomer.php">add Customers</a>
                <a href="customer/register.php">Register Product</a>
            </div>
        </div>

        <hr>
        <footer>
            &copy; 2025 SportsPro, Inc.
        </footer>
    </div>
</body>
</html>