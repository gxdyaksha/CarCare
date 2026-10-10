
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include 'includes/db.php';

$user_id = (int)$_SESSION["user_id"];
$message = "";
$messageType = "";

// Delete booking
if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["delete_booking"])
) {
    $booking_id = (int)($_POST["booking_id"] ?? 0);

    $delete_stmt = mysqli_prepare(
        $conn,
        "DELETE FROM bookings WHERE id = ? AND user_id = ?"
    );

    if ($delete_stmt) {
        mysqli_stmt_bind_param(
            $delete_stmt,
            "ii",
            $booking_id,
            $user_id
        );

        if (mysqli_stmt_execute($delete_stmt)) {
            if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
                mysqli_stmt_close($delete_stmt);
                mysqli_close($conn);

                header("Location: my_bookings.php?deleted=1");
                exit;
            } else {
                $message = "Booking not found.";
                $messageType = "error";
            }
        } else {
            $message = "Unable to delete booking. Please try again.";
            $messageType = "error";
        }

        mysqli_stmt_close($delete_stmt);
    } else {
        $message = "Something went wrong. Please try again.";
        $messageType = "error";
    }
}

// Display messages
if (isset($_GET["booked"]) && $_GET["booked"] == "1") {
    $message = "Your service booking was successful!";
    $messageType = "success";
}

if (isset($_GET["deleted"]) && $_GET["deleted"] == "1") {
    $message = "Booking deleted successfully!";
    $messageType = "success";
}

// Fetch bookings for the logged-in user
$stmt = mysqli_prepare(
    $conn,
    "SELECT
        bookings.id,
        cars.car_name,
        cars.car_number,
        bookings.service_type,
        bookings.service_date,
        bookings.status,
        bookings.amount
     FROM bookings
     INNER JOIN cars ON bookings.car_id = cars.id
     WHERE bookings.user_id = ?
     ORDER BY bookings.id DESC"
);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$bookings = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Bookings - CarCare</title>

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/my_bookings.css">
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>

<?php include 'includes/header.php'; ?>

<main class="bookings-page">

    <h1>My Bookings</h1>
    <p>Manage your car service bookings.</p>

    <?php if ($message !== ""): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($bookings) > 0): ?>

        <div class="booking-list">

            <?php
            // Display numbering starts from 1 on every page load
            $booking_number = 1;
            ?>

            <?php while ($booking = mysqli_fetch_assoc($bookings)): ?>

                <section class="booking-card">

                    <h2>
                        Booking #<?php echo $booking_number; ?>
                    </h2>

                    <p>
                        <strong>Car:</strong>
                        <?php echo htmlspecialchars($booking["car_name"]); ?>
                    </p>

                    <p>
                        <strong>Registration Number:</strong>
                        <?php echo htmlspecialchars($booking["car_number"]); ?>
                    </p>

                    <p>
                        <strong>Service:</strong>
                        <?php echo htmlspecialchars($booking["service_type"]); ?>
                    </p>

                    <p>
                        <strong>Service Date:</strong>
                        <?php echo htmlspecialchars($booking["service_date"]); ?>
                    </p>

                    <p>
                        <strong>Amount:</strong>
                        ₹<?php echo number_format((float)$booking["amount"], 2); ?>
                    </p>

                    <p>
                        <strong>Status:</strong>
                        <span class="status">
                            <?php echo htmlspecialchars($booking["status"]); ?>
                        </span>
                    </p>

                    <form
                        action="my_bookings.php"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this booking?');"
                    >
                        <input
                            type="hidden"
                            name="booking_id"
                            value="<?php echo (int)$booking["id"]; ?>"
                        >

                        <button
                            type="submit"
                            name="delete_booking"
                            class="delete-button"
                        >
                            Delete Booking
                        </button>
                    </form>

                </section>

                <?php $booking_number++; ?>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="empty-bookings">
            <h2>No Bookings Yet</h2>
            <p>You do not have any service bookings.</p>

            <a href="book_service.php" class="action-button">
                Book a Service
            </a>
        </div>

    <?php endif; ?>

    <a href="dashboard.php" class="back-link">
        ← Back to Dashboard
    </a>

</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
