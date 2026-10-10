<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <div class="navbar">

        <a href="index.php" class="logo">CarCare</a>

        <nav class="links">

            <a href="index.php">Home</a>

            <?php if (isset($_SESSION["user_id"])): ?>

                <a href="dashboard.php">Dashboard</a>
                <a href="services.php">Services</a>
                <a href="my_cars.php">My Cars</a>
                <a href="book_service.php">Book Service</a>
                <a href="my_bookings.php">My Bookings</a>

                <span class="welcome-user">
                    Hi, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
                </span>

                <a href="logout.php" class="logout-link">Logout</a>

            <?php else: ?>

                <a href="services.php">Services</a>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>

            <?php endif; ?>

        </nav>

    </div>
</header>
