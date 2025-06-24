<?php
require_once __DIR__ . '/../data/db.php';
// Get the 'search' query from the URL (GET request), default to an empty string if not provided
$search = $_GET['search'] ?? '';
if ($search !== '') {
    // If a search term is entered, prepare a parameterized query using LIKE
    $stmt = $db->prepare('SELECT * FROM customers WHERE lastname LIKE :search ORDER BY lastname');
    $stmt->execute([':search' => '%' . $search . '%']);
} else {
        // If no search term, just select all customers
    $stmt = $db->query('SELECT * FROM customers ORDER BY lastname');
}
// Fetch all matching customers into an array
$customers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Management</title>
</head>
<body style="background-color: #e0e7ff; margin: 0; padding: 0;">
<div style="max-width: 1100px; min-height: 600px; margin: 40px auto; background-color: white; padding: 40px 50px; border: 1px solid #aaa; box-sizing: border-box;">

    <h1>SportsPro Technical Support</h1>
    <p>Sports management software for sports enthusiasts</p>
    <p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>
    <hr style="height: 3px; background-color: black; border: none;">
    <h2>Customer Search</h2>
    <p><a href="addCustomer.php" style="font-weight: bold;">Add New Customer</a></p>
    <form method="get" action="" style="margin-bottom: 20px;">
    <label for="search">Last Name:</label>
    <input type="text" name="search" id="search" value="<?= htmlspecialchars($search) ?>" />
    <button type="submit">Search</button>
    <?php if ($search): ?>
        <a href="customers.php" style="margin-left: 10px;">Clear</a>
    <?php endif; ?>
</form>
    <h2>Results</h2>

    <table cellpadding="8" border="1" style="border-collapse: collapse; width: 100%;">
        <tr style="background-color: #dbeafe;">
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>Postal Code</th>
            <th>Country</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($customers as $cust): ?>
        <tr>
                    <!-- Loop through all customer records and display each row -->
            <td><?= htmlspecialchars($cust['customerID']) ?></td>
            <td><?= htmlspecialchars($cust['firstname']) ?></td>
            <td><?= htmlspecialchars($cust['lastname']) ?></td>
            <td><?= htmlspecialchars($cust['address']) ?></td>
            <td><?= htmlspecialchars($cust['city']) ?></td>
            <td><?= htmlspecialchars($cust['state']) ?></td>
            <td><?= htmlspecialchars($cust['postalCode']) ?></td>
            <td><?= htmlspecialchars($cust['countryCode']) ?></td>
            <td><?= htmlspecialchars($cust['phone']) ?></td>
            <td><?= htmlspecialchars($cust['email']) ?></td>
            <td>
                                <!-- Edit and Delete links -->
                <a href="editCustomer.php?customerID=<?= urlencode($cust['customerID']) ?>">Edit</a> |
                <a href="deleteCustomer.php?customerID=<?= urlencode($cust['customerID']) ?>" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <hr style="height: 3px; background-color: black; border: none;">
    <footer style="text-align: right; font-size: 0.9em; color: #333;">
      &copy; 2025 SportsPro, Inc.
    </footer>
</div>
</body>
</html>
