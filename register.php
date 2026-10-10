
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    include 'includes/db.php';

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $password);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: login.php?registered=1");
        exit;
    } else {
        $error = "Registration failed. This email may already exist.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CarCare</title>

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <main>
        <section class="register">
            <h1>Create an Account</h1>
            <p>Register to book your car service.</p>

            <?php
            if (isset($error)) {
                echo "<p>" . htmlspecialchars($error) . "</p>";
            }
            ?>

            <form action="register.php" method="POST">
                <div class="form">

                    <label for="name">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter Your Full Name"
                        required
                    >

                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter Your Email"
                        required
                    >

                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter Your Password"
                        required
                    >

                    <button type="submit">Register</button>

                </div>
            </form>

            <p>Already have an account?
                <a href="login.php">Login here</a>
            </p>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
```
