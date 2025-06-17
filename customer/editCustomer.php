<?php
require_once __DIR__ . '/../data/db.php';

$customerID = $_GET['customerID'] ?? null;
$error = '';
$success = '';

if (!$customerID) {
    die('Customer ID not provided.');
}

$stmt = $db->prepare('SELECT * FROM customers WHERE customerID = ?');
$stmt->execute([$customerID]);
$customer = $stmt->fetch();

if (!$customer) {
    die('Customer not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $postalCode = $_POST['postalCode'] ?? '';
    $countryCode = $_POST['countryCode'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($firstname && $lastname && $email) {
        try {
            $query = "UPDATE customers SET
                firstname = :firstname,
                lastname = :lastname,
                address = :address,
                city = :city,
                state = :state,
                postalCode = :postalCode,
                countryCode = :countryCode,
                phone = :phone,
                email = :email
                WHERE customerID = :customerID";

            $stmt = $db->prepare($query);
            $stmt->execute([
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':address' => $address,
                ':city' => $city,
                ':state' => $state,
                ':postalCode' => $postalCode,
                ':countryCode' => $countryCode,
                ':phone' => $phone,
                ':email' => $email,
                ':customerID' => $customerID
            ]);

            header('Location: customerManagement.php');
            exit;
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<body style="background-color: #e0e7ff; margin: 0; padding: 0;">
<div style="max-width: 1100px; min-height: 600px; margin: 40px auto; background-color: white; padding: 40px 50px; border: 1px solid #aaa; box-sizing: border-box;">

<h1 style="margin-top: 20px;">SportsPro Technical Support</h1>
<p>Sports management software for sports enthusiasts</p>
<p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>

<hr style="height: 3px; background-color: black; border: none;">

<h2 style="margin-top: 20px;">Edit Customer</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="post">
    <table style="margin-top: 10px; border-collapse: collapse;">
        <?php
        $fields = [
            'firstname' => 'First Name', 'lastname' => 'Last Name', 'address' => 'Address', 'city' => 'City',
            'state' => 'State', 'postalCode' => 'Postal Code', 'countryCode' => 'Country Code',
            'phone' => 'Phone', 'email' => 'Email'
        ];
        foreach ($fields as $name => $label) {
            $value = htmlspecialchars($customer[$name]);
            $required = in_array($name, ['firstname', 'lastname', 'email']) ? 'required' : '';
            $width = ($name === 'email' || $name === 'address') ? '350px' : '200px';
            $type = $name === 'email' ? 'email' : 'text';

            echo "<tr>";
            echo "<td style='padding: 8px; text-align: left;'><label for='{$name}' style='font-weight: bold;'>{$label}:</label></td>";
            echo "<td style='padding: 8px;'>";
            echo "<input type='{$type}' name='{$name}' id='{$name}' value='{$value}' style='width: {$width}; padding: 4px;' {$required}>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
        <tr>
            <td></td>
            <td style="padding: 8px;"><button type="submit" style="padding: 4px 10px;">Update Customer</button></td>
        </tr>
    </table>
</form>

<p><a href="customerManagement.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Back to Customer List</a></p>

<hr style="height: 3px; background-color: black; border: none;">
<footer style="text-align: right; font-size: 0.9em; color: #333;">
  &copy; 2025 SportsPro, Inc.
</footer>
</div>
</body>
