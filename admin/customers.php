<?php
session_start();

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

require_once "../includes/db.php";

$sql = "SELECT id, name, email FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Unable to fetch customers.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarCare - Customers</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-login" style="max-width: 900px; width: 95%;">
    <h1>Registered Customers</h1>

    <p>Total Customers: <?php echo mysqli_num_rows($result); ?></p>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 12px; background: #4736fe; color: white;">ID</th>
                <th style="padding: 12px; background: #4736fe; color: white;">Name</th>
                <th style="padding: 12px; background: #4736fe; color: white;">Email</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #ddd;">
                        <?php echo (int) $row["id"]; ?>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #ddd;">
                        <?php echo htmlspecialchars($row["name"] ?? ""); ?>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #ddd;">
                        <?php echo htmlspecialchars($row["email"] ?? ""); ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
