<?php
session_start(); // Start the session to track login state
require_once __DIR__ . '/../data/db.php';

$error = ''; // Error message placeholder
$email = '';// Store the entered email
// Handle form submission (only when form is posted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize inputs
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $incidentID = $_POST['incidentID'] ?? null; // Optional, if logging in for assignment
    $intent = $_POST['intent'] ?? null;
    // Clear any previous customer session (this is technician login)
    unset($_SESSION['customer']);
    unset($_SESSION['userRole']);
    // Validate email and password input
    if ($email && $password && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Retrieve technician by email
        $stmt = $db->prepare("SELECT * FROM technicians WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $tech = $stmt->fetch(PDO::FETCH_ASSOC);
        // Compare plain-text password (⚠️ should be hashed in production)
if ($tech && $password === $tech['password']) {
                // Set session variables for the logged-in technician
            $_SESSION['technician'] = $tech;
            $_SESSION['techID'] = $tech['techID'];
            $_SESSION['userRole'] = 'technician';
            // If the login intent is to assign an incident, redirect accordingly
            if ($intent === 'assign' && $incidentID) {
                $_SESSION['selectedIncidentID'] = $incidentID;
                header("Location: assign.php?from=tech");
                exit();
            }
            // Redirect to technician dashboard after login
header("Location:/technician/dashboard.php");           
 exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Please enter a valid email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Technician Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0e7ff;
            margin: 0;
            padding: 0;
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

        input, button {
            padding: 8px;
            font-size: 14px;
        }

        button {
            padding: 10px 18px;
            cursor: pointer;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
        }

        footer {
            text-align: right;
            font-size: 0.9em;
            color: #333;
            margin-top: 40px;
        }

        h1 {
            margin-top: 0;
        }

        a {
            text-decoration: none;
            color: #0000EE;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        label strong {
            display: block;
            margin-bottom: 5px;
            margin-top: 15px;
        }

        input[type="email"], input[type="password"] {
            width: 300px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>SportsPro Technical Support</h1>
    <p>Access your technician account</p>
    <p><a href="../index.php">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none;">

    <h2>Technician Login</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="email"><strong>Email:</strong></label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

        <label for="password"><strong>Password:</strong></label>
        <input type="password" id="password" name="password" required>

        <?php if (isset($_GET['incidentID'])): ?>
            <input type="hidden" name="incidentID" value="<?= htmlspecialchars($_GET['incidentID']) ?>">
            <input type="hidden" name="intent" value="assign">
        <?php endif; ?>

        <button type="submit">Login</button>
    </form>

    <hr style="height: 3px; background-color: black; border: none;">
    <footer>&copy; 2025 SportsPro, Inc.</footer>
</div>
</body>
</html>