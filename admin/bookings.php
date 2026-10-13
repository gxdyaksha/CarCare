<?php
session_start();

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

require_once "../includes/db.php";

$sql = "SELECT
            bookings.id,
            users.name AS customer_name,
            users.email,
            cars.car_name,
            cars.car_number,
            bookings.service_type,
            bookings.service_date,
            bookings.status,
            bookings.amount
        FROM bookings
        INNER JOIN users ON bookings.user_id = users.id
        INNER JOIN cars ON bookings.car_id = cars.id
        ORDER BY bookings.id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Unable to fetch bookings.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarCare - Service Bookings</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-login" style="max-width: 1200px; width: 96%;">
    <h1>Service Bookings</h1>

    <p>Total Bookings: <?php echo mysqli_num_rows($result); ?></p>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="padding: 10px; background: #4736fe; color: white;">ID</th>
                    <th style="padding: 10px; background: #4736fe; color: white;">Customer</th>
                    <th style="padding: 10px; background: #4736fe; color: white;">Car</th>
                    <th style="padding: 10px; background: #4736fe; color: white;">Car Number</th>
                    <th style="padding: 10px; background: #4736fe; color: white;">Service</th>
                    <th style="padding: 10px; background: #4736fe; color: white;">Date</th>
                    <th style="padding: 10px; background: #4736fe; color: white;">Status</th>
                    <th style="padding: 10px; background: #4736fe; color: white;">Amount</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <?php echo (int) $row["id"]; ?>
                        </td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <?php echo htmlspecialchars($row["customer_name"] ?? ""); ?>
                            <br>
                            <small><?php echo htmlspecialchars($row["email"] ?? ""); ?></small>
                        </td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <?php echo htmlspecialchars($row["car_name"] ?? ""); ?>
                        </td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <?php echo htmlspecialchars($row["car_number"] ?? ""); ?>
                        </td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <?php echo htmlspecialchars($row["service_type"] ?? ""); ?>
                        </td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <?php echo htmlspecialchars($row["service_date"] ?? ""); ?>
                        </td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <?php echo htmlspecialchars($row["status"] ?? "Pending"); ?>
                        </td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                            ₹<?php echo number_format((float) $row["amount"], 2); ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
