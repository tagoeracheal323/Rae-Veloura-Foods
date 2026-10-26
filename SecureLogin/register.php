<?php
require_once 'db.php';

$message = "";

if (isset($_POST["register"])) {

    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || !filter_var($email, FILTER_VALIDATE_EMAIL) || $password === "") {
        $message = "Enter a username, valid email, and password.";
    } else {
        $check = $conn->prepare("SELECT ID FROM users WHERE Email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        $emailExists = $check->num_rows > 0;
        $check->close();

        if ($emailExists) {
            $message = "Email already registered.";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare(
                "INSERT INTO users (Username, Email, Password) VALUES (?, ?, ?)"
            );
            $insert->bind_param("sss", $username, $email, $passwordHash);
            $message = $insert->execute()
                ? "Registration successful! You can now login."
                : "Registration failed. Please try again.";
            $insert->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Register | Rae Veloura Foods</title>
    <link rel="stylesheet" href="styles.css?v=20261026-4">
</head>

<body>
<main class="page-shell">
    <section class="page-card">
        <h1 class="page-brand">Rae Veloura Foods</h1>
        <p class="brand-tagline">Elegance in Every Bite</p>

        <h2>Create Account</h2>

        <?php if (isset($message)): ?>
            <p class="page-message">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <p><button type="submit" name="register">Create Account</button></p>
        </form>

        <p><a href="login.php">Already have an account? Sign in</a></p>
    </section>
</main>

</body>

</html>