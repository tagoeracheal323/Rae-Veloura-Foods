<?php
session_start();

if (!isset($_SESSION['user_id']) ||
    ($_SESSION['otp_verified'] ?? false) !== true) {
    header("Location: login.php");
    exit();
}

if (($_SESSION['role'] ?? '') !== 'Manager') {
    http_response_code(403);
    exit("Access denied. Managers only.");
}

require_once 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($password) < 8) {
        $message = "Enter a username, valid email, and password of at least 8 characters.";
    } else {
        $stmt = $conn->prepare(
            "SELECT ID FROM users WHERE Email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "That email is already registered.";
            $stmt->close();
        } else {
            $stmt->close();

            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = "Inputer";

            $stmt = $conn->prepare(
                "INSERT INTO users (Username, Email, Password, role)
                 VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "ssss",
                $username,
                $email,
                $hash,
                $role
            );

            $message = $stmt->execute()
                ? "Inputer account created successfully!"
                : "Unable to create the account.";

            $stmt->close();
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
    <title>Add Inputer | Rae Veloura Foods</title>
    <link rel="stylesheet" href="styles.css?v=20261026-4">
</head>
<body>
    <main class="page-shell">
        <section class="page-card">
            <h1 class="page-brand">Rae Veloura Foods</h1>
            <p class="brand-tagline">Team Management</p>
            <h2>Add a New Inputer</h2>

            <?php if ($message !== ""): ?>
                <p class="page-message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <form method="POST">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Temporary Password</label>
                <input type="password" id="password" name="password" minlength="8" required>

                <p><button type="submit">Create Inputer</button></p>
            </form>

            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </section>
    </main>
</body>
</html>