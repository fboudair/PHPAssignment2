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
        <hr>

        <div class="login-links">
            <div class="login-section">
                <h1>Administrators</h1>
                <a href="admin/index.php">Manage Products</a>
                <a href="admin/customerlogin.php">Register Products</a>
                <a href="technician/techManager.php">Manage Technicians</a>
                <a href="customer/customerManagement.php">Manage Customers</a>
                <a href="incident/getcustomer.php">Create Incident</a>
                <a href="incident/assign.php">Assign Incident</a>
                <a href="admin/login.php">Display Incidents</a>
                <a href="admin/login.php">Administrator Login</a>
            </div>

            <div class="login-section">
                <h1>Technicians</h1>
                <a href="admin/login.php">Update Incidents</a>
            </div>

            <div class="login-section">
                <h1>Customers</h1>
                <a href="admin/login.php">Register Product</a>
            </div>
        </div>

        <hr>
        <footer>
            &copy; 2025 SportsPro, Inc.
        </footer>
    </div>
</body>
</html>
