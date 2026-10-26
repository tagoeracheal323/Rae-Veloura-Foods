<?php
session_start();

if (!isset($_SESSION['user_id']) ||
    ($_SESSION['otp_verified'] ?? false) !== true) {
    header("Location: login.php");
    exit();
}

if (($_SESSION['role'] ?? '') !== 'Administrator') {
    http_response_code(403);
    exit("Access denied. Administrators only.");
}

require_once 'db.php';

$result = $conn->query(
    "SELECT ID, Username, Email, role FROM users ORDER BY ID"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Manage Users | Rae Veloura Foods</title>
    <link rel="stylesheet" href="styles.css?v=20261026-4">
</head>
<body>
    <main class="page-shell">
        <section class="page-card">
            <h1 class="page-brand">Rae Veloura Foods</h1>
            <p class="brand-tagline">User Management</p>
            <h2>User Management</h2>

            <table class="data-table">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>

                <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo (int)$user['ID']; ?></td>
                    <td><?php echo htmlspecialchars($user['Username'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user['Email'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>

            <p><a href="dashboard.php">Back to Dashboard</a></p>
        </section>
    </main>
</body>
</html>