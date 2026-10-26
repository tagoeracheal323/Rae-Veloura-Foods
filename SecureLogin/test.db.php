<?php
require_once 'db.php';
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Connection Check | Rae Veloura Foods</title>
    <link rel="stylesheet" href="styles.css?v=20261026-4">
</head>
<body>
    <main class="page-shell">
        <section class="page-card">
            <h1 class="page-brand">Rae Veloura Foods</h1>
            <p class="brand-tagline">Connection Check</p>
            <p>Database connection successful.</p>
            <p><a href="index.php">Return to home</a></p>
        </section>
    </main>
</body>
</html>