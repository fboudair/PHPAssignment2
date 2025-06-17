<?php
require_once __DIR__ . '/../data/db.php';
$code = $_GET['productCode'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $version = $_POST['version'];
    $releaseDate = $_POST['releaseDate'];

    $sql = 'UPDATE products SET name = ?, version = ?, releaseDate = ? WHERE productCode = ?';
    $stmt = $db->prepare($sql);
    $stmt->execute([$name, $version, $releaseDate, $code]);

    header('Location: index.php');
    exit();
}

$stmt = $db->prepare('SELECT * FROM products WHERE productCode = ?');
$stmt->execute([$code]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
</head>
<body style="background-color: #e0e7ff; margin: 0; padding: 0;">
<div style="max-width: 900px; margin: 40px auto; background-color: white; padding: 40px 50px; border: 1px solid #aaa; box-sizing: border-box;">

    <h1 style="margin-top: 0;">Sports Pro Technical Support</h1>
    <p style="margin-bottom: 5px;">Sports management software for sports enthusiasts</p>
    <p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 20px;">

    <h2 style="margin-top: 20px;">Edit Product</h2>
    <form method="post">
        <table style="margin-top: 10px; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px; text-align: right;"><strong>Code:</strong></td>
                <td style="padding: 8px;"><?= htmlspecialchars($product['productCode']) ?></td>
            </tr>
            <tr>
                <td style="padding: 8px; text-align: right;"><label for="name"><strong>Name:</strong></label></td>
                <td style="padding: 8px;"><input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required style="width: 200px; padding: 4px;"></td>
            </tr>
            <tr>
                <td style="padding: 8px; text-align: right;"><label for="version"><strong>Version:</strong></label></td>
                <td style="padding: 8px;"><input type="text" id="version" name="version" value="<?= htmlspecialchars($product['version']) ?>" required style="width: 200px; padding: 4px;"></td>
            </tr>
            <tr>
                <td style="padding: 8px; text-align: right;"><label for="releaseDate"><strong>Release Date:</strong></label></td>
                <td style="padding: 8px;">
                    <input type="date" id="releaseDate" name="releaseDate" value="<?= htmlspecialchars($product['releaseDate']) ?>" required style="width: 200px; padding: 4px;">
                    <span style="font-size: 0.85em;">Use 'yyyy-mm-dd' format</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="padding: 8px;"><button type="submit" style="padding: 4px 10px;">Update</button></td>
            </tr>
        </table>
    </form>

    <p><a href="index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Back to Products</a></p>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 30px;">

    <footer style="text-align: right; font-size: 0.9em; color: #333;">
        &copy; 2025 SportsPro, Inc.
    </footer>
</div>
</body>
</html>
