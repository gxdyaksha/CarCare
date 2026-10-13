<?php
session_start();
require_once "../includes/db.php";

$error = "";

if (isset($_SESSION["admin_logged_in"])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Temporary admin credentials
    if ($username === "admin" && $password === "CarCare@123") {
        session_regenerate_id(true);
        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_username"] = "admin";

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarCare Admin Login</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-login">
    <h1>CarCare Admin</h1>
    <p>Login to manage customers and services</p>

    <?php if ($error != "") { ?>
        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php } ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <a href="../index.php">Back to Website</a>
</div>

</body>
</html>
