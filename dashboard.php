
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CarCare</title>

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="dashboard">
    <section class="welcome">
        <h1>Welcome to CarCare!</h1>
        <p>
            Hello, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>.
            Manage your car and service bookings from here.
        </p>
    </section>

    <section class="dashboard-grid">

        <a href="my_cars.php" class="dashboard-card">
            <span class="card-icon">🚗</span>
            <h2>My Cars</h2>
            <p>Add and manage your cars.</p>
            <span class="card-button">Manage Cars →</span>
        </a>

        <a href="book_service.php" class="dashboard-card">
            <span class="card-icon">🔧</span>
            <h2>Book a Service</h2>
            <p>Book a service for your car.</p>
            <span class="card-button">Book Now →</span>
        </a>

        <a href="my_bookings.php" class="dashboard-card">
            <span class="card-icon">📅</span>
            <h2>My Bookings</h2>
            <p>View your bookings and their status.</p>
            <span class="card-button">View Bookings →</span>
        </a>

        <a href="services.php" class="dashboard-card">
            <span class="card-icon">🛠️</span>
            <h2>Our Services</h2>
            <p>Explore our available car services.</p>
            <span class="card-button">View Services →</span>
        </a>

    </section>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>
