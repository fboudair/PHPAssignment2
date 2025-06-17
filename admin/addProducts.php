<?php
require_once __DIR__ . '/../data/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['productCode'];
    $name = $_POST['name'];
    $version = $_POST['version'];
    $releaseDate = $_POST['releaseDate'];

    $sql = 'INSERT INTO products (productCode, name, version, releaseDate) VALUES (?, ?, ?, ?)';
    $stmt = $db->prepare($sql);
    $stmt->execute([$code, $name, $version, $releaseDate]);

    header('Location: index.php');
    exit();
}
?>
<body style="background-color: #e0e7ff; margin: 0; padding: 0;">
<div style="max-width: 1100px; min-height: 600px; margin: 40px auto; background-color: white; padding: 40px 50px; border: 1px solid #aaa; box-sizing: border-box;">

<h1 style="margin-top: 20px;">Sports Pro Technical Support</h1>
<p>Sports management software for sports enthutiasts</p>
<p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>

<hr style="height: 3px; background-color: black; border: none;">

<h2 style="margin-top: 20px;">Add Product</h2>
<form method="post">
    <table style="margin-top: 10px; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px; text-align: left;"><label for="productCode" style="font-weight: bold;">Code:</label></td>
            <td style="padding: 8px;"><input type="text" name="productCode" id="productCode" required style="width: 200px; padding: 4px;"></td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;"><label for="name" style="font-weight: bold;">Name:</label></td>
            <td style="padding: 8px;"><input type="text" name="name" id="name" required style="width: 200px; padding: 4px;"></td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;"><label for="version" style="font-weight: bold;">Version:</label></td>
            <td style="padding: 8px;"><input type="text" name="version" id="version" required style="width: 200px; padding: 4px;"></td>
        </tr>
        <tr>
            <td style="padding: 8px; text-align: left;"><label for="releaseDate" style="font-weight: bold;">Release Date:</label></td>
            <td style="padding: 8px;">
                <input type="date" name="releaseDate" id="releaseDate" required style="width: 200px; padding: 4px;">
                <span style="font-size: 0.85em;">Use 'yyyy-mm-dd' format</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="padding: 8px;"><button type="submit" style="padding: 4px 10px;">Add Product</button></td>
        </tr>
    </table>
</form>

<p><a href="index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">View Product List</a></p>
<hr style="height: 3px; background-color: black; border: none;">
    <footer style="text-align: right; font-size: 0.9em; color: #333;">
      &copy; 2025 SportsPro, Inc.
    </footer>
</div>
</body>