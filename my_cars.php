<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";

include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION["user_id"];
    $car_name = trim($_POST["car_name"]);
    $car_number = strtoupper(trim($_POST["car_number"]));

    if ($car_name != "" && $car_number != "") {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO cars (user_id, car_name, car_number)
             VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iss",
            $user_id,
            $car_name,
            $car_number
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Car added successfully!";
        } else {
            $message = "Unable to add car. Please try again.";
        }

        mysqli_stmt_close($stmt);

    } else {
        $message = "Please fill in all fields.";
    }
}

$user_id = $_SESSION["user_id"];

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, car_name, car_number FROM cars WHERE user_id = ?"
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

    <title>My Cars - CarCare</title>

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/my_cars.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
    <h1>My Cars</h1>
    <p>Add your car details before booking a service.</p>

    <?php if ($message != ""): ?>
        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <section class="car-form">
        <h2>Add Your Car</h2>

        <form action="my_cars.php" method="POST">

            <label for="car_name">Car Name / Model</label>
            <input
                type="text"
                id="car_name"
                name="car_name"
                placeholder="e.g. Maruti Swift"
                required
            >

            <label for="car_number">Registration Number</label>
            <input
                type="text"
                id="car_number"
                name="car_number"
                placeholder="e.g. MH02AB1234"
                required
            >

            <button type="submit">Add Car</button>
        </form>
    </section>

    <section class="car-list">
        <h2>My Registered Cars</h2>

        <?php if (mysqli_num_rows($cars) > 0): ?>

            <?php while ($car = mysqli_fetch_assoc($cars)): ?>
                <div class="car-item">
                    <h3>
                        <?php echo htmlspecialchars($car["car_name"]); ?>
                    </h3>

                    <p>
                        Registration:
                        <?php echo htmlspecialchars($car["car_number"]); ?>
                    </p>
                </div>
            <?php endwhile; ?>

        <?php else: ?>

            <p>No cars added yet.</p>

        <?php endif; ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
