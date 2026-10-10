<?php
session_start();

$services = [
    [
        "name" => "General Service",
        "price" => 1500,
        "description" => "Complete car inspection, basic maintenance and general servicing.",
        "icon" => "🔧"
    ],
    [
        "name" => "Oil Change",
        "price" => 800,
        "description" => "Engine oil replacement to help maintain smooth engine performance.",
        "icon" => "🛢️"
    ],
    [
        "name" => "AC Service",
        "price" => 1200,
        "description" => "Car AC inspection and basic servicing for better cooling.",
        "icon" => "❄️"
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Our Services - CarCare</title>

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/services.css">
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>

<?php include 'includes/header.php'; ?>

<main class="services-page">

    <section class="services-heading">
        <h1>Our Car Services</h1>
        <p>Reliable car care services to keep your vehicle running smoothly.</p>
    </section>

    <section class="services-grid">

        <?php foreach ($services as $service): ?>

            <article class="service-card">

                <div class="service-icon">
                    <?php echo $service["icon"]; ?>
                </div>

                <h2>
                    <?php echo htmlspecialchars($service["name"]); ?>
                </h2>

                <p class="service-description">
                    <?php echo htmlspecialchars($service["description"]); ?>
                </p>

                <p class="service-price">
                    ₹<?php echo number_format($service["price"]); ?>
                </p>

                <a href="book_service.php" class="service-button">
                    Book This Service
                </a>

            </article>

        <?php endforeach; ?>

    </section>

    <div class="back-dashboard">
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>

</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>
