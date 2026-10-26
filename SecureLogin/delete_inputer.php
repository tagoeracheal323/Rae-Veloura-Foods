<?php
session_start();

if (!isset($_SESSION['user_id']) ||
    ($_SESSION['otp_verified'] ?? false) !== true) {
    header("Location: login.php");
    exit();
}

if (($_SESSION['role'] ?? '') !== 'Admin') {
    http_response_code(403);
    exit("Access denied. Admin only.");
}

require_once 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "user_id", FILTER_VALIDATE_INT);

    if (!$id) {
        $message = "Invalid user ID.";
    } else {
        $stmt = $conn->prepare(
            "DELETE FROM users WHERE ID = ? AND role = 'Inputer'"
        );
        $stmt->bind_param("i", $id);

        $stmt->execute();

        $message = $stmt->affected_rows > 0
            ? "Inputer account deleted successfully."
            : "No matching Inputer account was found.";

        $stmt->close();
    }
}

$result = $conn->query(
    "SELECT ID, Username, Email FROM users WHERE role = 'Inputer'"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Delete Inputer | Rae Veloura Foods</title>
    <link rel="stylesheet" href="styles.css?v=20261026-4">
</head>
<body>
    <main class="page-shell">
        <section class="page-card">
            <h1 class="page-brand">Rae Veloura Foods</h1>
            <p class="brand-tagline">Team Management</p>
            <h2>Delete Inputer Account</h2>

            <?php if ($message !== ""): ?>
                <p class="page-message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <?php while ($user = $result->fetch_assoc()): ?>
                <form method="POST" class="user-row">
                    <p>
                        <?php echo htmlspecialchars($user['Username'], ENT_QUOTES, 'UTF-8'); ?>
                        &mdash;
                        <?php echo htmlspecialchars($user['Email'], ENT_QUOTES, 'UTF-8'); ?>

                        <input type="hidden" name="user_id"
                               value="<?php echo (int)$user['ID']; ?>">

                        <button type="submit"
                                onclick="return confirm('Delete this Inputer account?')">
                            Delete
                        </button>
                    </p>
                </form>
            <?php endwhile; ?>

            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </section>
    </main>
</body>
</html>