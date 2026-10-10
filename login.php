<?php
session_start();

$message = "";
$messageType = "";

if (isset($_GET["registered"]) && $_GET["registered"] == "1") {
    $message = "Registration successful! Please login.";
    $messageType = "success";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    include 'includes/db.php';

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, name, email, password
         FROM users
         WHERE email = ?"
    );

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $user["email"];

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            header("Location: dashboard.php");
            exit;

        } elseif ($user) {

            $message = "Wrong Password!";
            $messageType = "error";

        } else {

            $message = "User Not Found!";
            $messageType = "error";

        }

        mysqli_stmt_close($stmt);

    } else {

        $message = "Something went wrong. Please try again.";
        $messageType = "error";

    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - CarCare</title>

    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/footer.css">
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <main>
        <section class="login">

            <h1>Login</h1>
            <p>Login to book your car service.</p>

            <?php if ($message != ""): ?>
                <p class="<?php echo htmlspecialchars($messageType); ?>">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            <?php endif; ?>

            <form action="login.php" method="POST">

                <div class="form">

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

                    <button type="submit">Login</button>

                </div>

            </form>

            <p>
                Don't have an account?
                <a href="register.php">Register here</a>
            </p>

        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

</body>
</html>
