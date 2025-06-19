<?php
session_start();
require_once __DIR__ . '/../data/db.php';

$sql = "SELECT t.techID, t.firstName, t.lastName, t.email,
        (
            SELECT COUNT(*) 
            FROM incidents i
            WHERE i.techID = t.techID AND i.dateClosed IS NULL
        ) AS openIncidents
        FROM technicians t
        ORDER BY t.lastName, t.firstName";

$stmt = $db->prepare($sql);
$stmt->execute();
$technicians = $stmt->fetchAll();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['techID']) && isset($_SESSION['selectedIncidentID'])) {
    $techID = $_POST['techID'];
    $incidentID = $_SESSION['selectedIncidentID'];

    $updateStmt = $db->prepare("UPDATE incidents SET techID = :techID WHERE incidentID = :incidentID");
    $updateStmt->execute([':techID' => $techID, ':incidentID' => $incidentID]);

    $message = "Incident #$incidentID has been assigned to technician ID #$techID.";
    unset($_SESSION['selectedIncidentID']);
}
?>

<body style="background-color: #e0e7ff; margin: 0; padding: 0;">
<div style="max-width: 1100px; min-height: 600px; margin: 40px auto; background-color: white; padding: 40px 50px; border: 1px solid #aaa; box-sizing: border-box;">

<h1 style="margin-top: 20px;">SportsPro Technical Support</h1>
<p>Sports management software for sports enthusiasts</p>
<p><a href="../index.php" style="font-weight: bold; color: #0000EE; text-decoration: underline;">Home</a></p>

<hr style="height: 3px; background-color: black; border: none;">
<h2 style="margin-top: 20px;">Select Technician</h2>

<?php if ($message): ?>
    <p style="color: green; font-weight: bold; margin-bottom: 20px;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<?php if (count($technicians) > 0): ?>
<form method="post" style="margin-top: 10px;">
    <table style="border-collapse: collapse; width: 100%; font-size: 14px;">
        <thead>
 <tr style="background-color: #dbeafe;">
                    <th style="padding: 8px; border: 1px solid #aaa; text-align: left;">Name</th>
                <th style="padding: 8px; border: 1px solid #aaa; text-align: left;">Email</th>
                <th style="padding: 8px; border: 1px solid #aaa; text-align: left;">Open Incidents</th>
                <th style="padding: 8px; border: 1px solid #aaa; text-align: left;">Select</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($technicians as $tech): ?>
            <tr>
                <td style="padding: 8px; border: 1px solid #aaa;"><?= htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']) ?></td>
                <td style="padding: 8px; border: 1px solid #aaa;"><?= htmlspecialchars($tech['email']) ?></td>
                <td style="padding: 8px; border: 1px solid #aaa;"><?= htmlspecialchars($tech['openIncidents']) ?></td>
               <td style="padding: 8px; border: 1px solid #aaa;">
    <button type="submit" name="techID" value="<?= htmlspecialchars($tech['techID']) ?>">
        Select
    </button>
</td>

            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>
<?php else: ?>
    <p>No technicians found.</p>
<?php endif; ?>

<hr style="height: 3px; background-color: black; border: none; margin-top: 40px; margin-bottom: 40px;">

<footer style="text-align: right; font-size: 0.9em; color: #333;">
  &copy; 2025 SportsPro, Inc.
</footer>
</div>
</body>