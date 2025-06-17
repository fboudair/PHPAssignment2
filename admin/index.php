<?php
require_once __DIR__ . '/../data/db.php';


$query = $db->query('SELECT * FROM products ORDER BY name');
$products = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management</title>
</head>
<body style="background-color: #e0e7ff; margin: 0; padding: 0;">
<div style="max-width: 900px; margin: 40px auto; background-color: white; padding: 40px 50px; border: 1px solid #aaa; box-sizing: border-box;">

    <h1 style="margin-top: 0;">Sports Pro Technical Support</h1>
    <p style="margin-bottom: 5px;">Sports management software for sports enthusiasts</p>
    <p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>

    <hr style="height: 3px; background-color: black; border: none; margin-top: 20px;">

    <h2>Product List</h2>

    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <tr>
            <th style="border: 1px solid;  padding: 8px;text-align:left;">Code</th>
            <th style="border: 1px solid; padding: 8px;text-align:left;">Name</th>
            <th style="border: 1px solid;  padding: 8px;text-align:left;">Version</th>
            <th style="border: 1px solid; border-right: none; padding: 8px;text-align:left;">Release Date</th>
    <th style="border-top: 1px solid; border-bottom: 1px solid; border-left: 1px dashed; border-right: 1px solid;  padding: 8px;"></th>
    </tr>
        <?php foreach ($products as $product): ?>
    <tr>
        <td style="border: 1px solid; padding: 8px;"><?= htmlspecialchars($product['productCode']) ?></td>
        <td style="border: 1px solid; padding: 8px;"><?= htmlspecialchars($product['name']) ?></td>
        <td style="border: 1px solid; padding: 8px;"><?= htmlspecialchars($product['version']) ?></td>
        <td style="border: 1px solid; border-right: none; padding: 8px;"><?= htmlspecialchars($product['releaseDate']) ?></td>
        <td style="border: 1px solid; border-left: 1px dashed; padding: 8px;">
            <form action="edit.php" method="get" style="display: inline;">
                <input type="hidden" name="productCode" value="<?= htmlspecialchars($product['productCode']) ?>">
                <button type="submit">Edit</button>
            </form>

            <form action="delete.php" method="get" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                <input type="hidden" name="productCode" value="<?= htmlspecialchars($product['productCode']) ?>">
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>

    </table>
<p><a href="addProducts.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Add Product</a></p>
<hr style="height: 3px; background-color: black; border: none;">
    <footer style="text-align: right; font-size: 0.9em; color: #333;">
      &copy; 2025 SportsPro, Inc.
    </footer>
</div>
</body>