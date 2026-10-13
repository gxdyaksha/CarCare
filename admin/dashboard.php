<?php
session_start();

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarCare Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-login">
    <h1>CarCare Admin Panel</h1>
    <p>Welcome, Admin!</p>

    <a href="customers.php">View Customers</a>
    <a href="bookings.php">View Service Bookings</a>
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
