
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include 'includes/db.php';

$message = "";
$user_id = $_SESSION["user_id"];

$services = [
    "General Service" => 1500,
    "Oil Change" => 800,
    "AC Service" => 1200
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $car_id = (int)($_POST["car_id"] ?? 0);
    $service_type = $_POST["service_type"] ?? "";
    $service_date = $_POST["service_date"] ?? "";

    if (
        !array_key_exists($service_type, $services) ||
        $service_date == "" ||
        $service_date < date("Y-m-d")
    ) {
        $message = "Please select a valid car, service and date.";
    } else {
        // Make sure the selected car belongs to the logged-in user.
        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM cars WHERE id = ? AND user_id = ?"
        );

        mysqli_stmt_bind_param($check, "ii", $car_id, $user_id);
        mysqli_stmt_execute($check);
        $car_result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($car_result) == 0) {
            $message = "Please select one of your registered cars.";
        } else {
            $amount = $services[$service_type];

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO bookings
                (user_id, car_id, service_type, service_date, amount)
                VALUES (?, ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "iissd",
                $user_id,
                $car_id,
                $service_type,
                $service_date,
                $amount
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_stmt_close($check);
                mysqli_close($conn);

                header("Location: my_bookings.php?booked=1");
                exit;
            } else {
                $message = "Booking failed. Please try again.";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
    }
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, car_name, car_number
     FROM cars
     WHERE user_id = ?
     ORDER BY id DESC"
);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$cars = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Service - CarCare</title>

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/book_service.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main class="booking-page">
    <h1>Book a Car Service</h1>
    <p>Select your car, service and preferred date.</p>

    <?php if ($message != ""): ?>
        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <?php if (mysqli_num_rows($cars) > 0): ?>

        <form action="book_service.php" method="POST" class="booking-form">

            <label for="car_id">Select Your Car</label>
            <select id="car_id" name="car_id" required>
                <option value="">Choose your car</option>

                <?php while ($car = mysqli_fetch_assoc($cars)): ?>
                    <option value="<?php echo $car["id"]; ?>">
                        <?php
                        echo htmlspecialchars(
                            $car["car_name"] . " - " . $car["car_number"]
                        );
                        ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="service_type">Select Service</label>
            <select id="service_type" name="service_type" required>
                <option value="">Choose a service</option>

                <?php foreach ($services as $name => $price): ?>
                    <option value="<?php echo htmlspecialchars($name); ?>">
                        <?php
                        echo htmlspecialchars($name) . " - ₹" .
                             number_format($price);
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="service_date">Preferred Service Date</label>
            <input
                type="date"
                id="service_date"
                name="service_date"
                min="<?php echo date('Y-m-d'); ?>"
                required
            >

            <button type="submit">Book Now</button>
        </form>

    <?php else: ?>

        <div class="no-cars">
            <p>You haven't added a car yet.</p>
            <a href="my_cars.php" class="action-button">Add Your Car</a>
        </div>

    <?php endif; ?>

    <p class="back-link">
        <a href="dashboard.php">← Back to Dashboard</a>
    </p>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
