<?php
session_start();

if (!isset($_SESSION['user_id'], $_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (($_SESSION['otp_verified'] ?? false) !== true) {
    header("Location: send_otp.php");
    exit();
}

$role = $_SESSION['role'] ?? 'Inputer';

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="color-scheme" content="only light">

<title>Rae Veloura Foods | Dashboard</title>
<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #ffffff !important;
    background-color: #ffffff !important;
    background-image: none !important;
    color-scheme: light !important;
    color: #10294b;
}

html {
    min-height: 100%;
    background: #ffffff !important;
    background-color: #ffffff !important;
    color-scheme: only light !important;
}

body,
header,
aside,
main,
footer,
.section {
    background: #ffffff !important;
    background-color: #ffffff !important;
    background-image: none !important;
}

header {
    background: #ffffff;
    color: #10294b;
    padding: 22px 5%;
}

.brand-area {
    display: flex;
    align-items: center;
    gap: 18px;
}

.brand {
    color: #b08a2e;
    font-family: Georgia, serif;
    font-size: 25px;
    font-weight: bold;
    letter-spacing: 1px;
}

.tagline {
    color: #10294b;
    font-size: 12px;
    margin-top: 8px;
    letter-spacing: 1px;
}

.layout {
    display: flex;
    min-height: 75vh;
}

aside {
    width: 220px;
    flex-shrink: 0;
    background: #ffffff;
    padding: 25px 16px;
}

aside h3 {
    color: #b08a2e;
    font-size: 13px;
    text-transform: uppercase;
}

aside a {
    display: block;
    color: #10294b;
    padding: 12px;
    margin: 8px 0;
    text-decoration: none;
    border-radius: 5px;
}

aside a:hover {
    background: #f7f4eb;
}

main {
    flex: 1;
    min-width: 0;
    padding: 30px;
}

.welcome h1 {
    font-size: 27px;
    color: #b08a2e;
}

.welcome p {
    color: #10294b;
    line-height: 1.6;
}

.gold {
    color: #b08a2e;
}

.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 18px;
    margin: 25px 0;
}

.card {
    background: white;
    padding: 22px;
    border-radius: 8px;
    box-shadow: 0 3px 12px rgba(16, 41, 75, 0.06);
}

.card h3 {
    font-size: 15px;
    margin-top: 0;
    color: #b08a2e;
}

.card p {
    line-height: 1.5;
    overflow-wrap: anywhere;
    color: #10294b;
}

.price {
    color: #b08a2e;
    font-size: 25px;
    font-weight: bold;
}

.section {
    background: white;
    padding: 24px;
    margin-top: 24px;
    border-radius: 8px;
}

.action {
    display: inline-block;
    background: #10294b;
    color: #e6c66b;
    padding: 13px 22px;
    margin: 8px 8px 8px 0;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    border: 0;
    cursor: pointer;
}

.action:hover {
    background: #183b69;
}

.logout {
    background: white;
}

footer {
    padding: 20px;
    text-align: center;
    background: #ffffff;
    color: #10294b;
    font-size: 12px;
}

@media (max-width: 650px) {
    .layout {
        flex-direction: column;
    }

    aside {
        width: 100%;
    }

    main {
        padding: 20px 14px;
    }

    .brand {
        font-size: 18px;
    }

}
</style>
<link rel="stylesheet" href="styles.css?v=20261026-4">
</head>

<body>

<header>
    <div class="brand-area">
        <div>
            <div class="brand">RAE VELOURA FOODS</div>
            <div class="tagline">QUALITY · EXCELLENCE · SERVICE</div>
        </div>
    </div>
</header>

<div class="layout">

<aside>
    <h3>Navigation</h3>

    <a href="dashboard.php">Dashboard</a>

    <?php if ($role === 'Manager'): ?>
        <a href="add_inputer.php">Add Inputer</a>
    <?php endif; ?>

    <?php if ($role === 'Admin'): ?>
        <a href="delete_inputer.php">Delete Inputer</a>
    <?php endif; ?>

    <?php if ($role === 'Administrator'): ?>
        <a href="manage_users.php">Manage Users</a>
    <?php endif; ?>

    <a href="logout.php">Sign Out</a>
</aside>

<main>

    <div class="welcome">
        <h1>Welcome back, <?php echo $username; ?>!</h1>
        <p>Welcome to the Rae Veloura Foods management portal.</p>
    </div>

    <div class="cards summary-cards">

        <div class="card">
            <h3>Signed-in User</h3>
            <p><?php echo $username; ?></p>
        </div>

        <div class="card">
            <h3>Account Role</h3>
            <p class="gold">
                <?php echo htmlspecialchars($role, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        </div>

        <div class="card">
            <h3>Account Email</h3>
            <p><?php echo $email !== '' ? $email : 'Not provided'; ?></p>
        </div>

        <div class="card">
            <h3>Security Status</h3>
            <p class="gold">Verified</p>
        </div>

    </div>

    <div class="section">
        <h2>Quick Overview</h2>
        <p>You have successfully signed in and completed two-factor verification.</p>
        <p>Your available functions depend on your assigned account role.</p>
    </div>

    <div class="section">
        <h2>Shop Rae Veloura Foods</h2>
        <p>Discover our signature fried rice packages.</p>

        <div class="cards product-cards">

            <div class="card">
                <h3>The Veloura Classic</h3>
                <p class="price">GH₵30</p>
                <p>Fried rice, chicken and salad.</p>
            </div>

            <div class="card">
                <h3>The Veloura Delight</h3>
                <p class="price">GH₵40</p>
                <p>Fried rice, chicken, salad and plantain.</p>
            </div>

            <div class="card">
                <h3>The Veloura Signature</h3>
                <p class="price">GH₵50</p>
                <p>Fried rice, chicken, salad, plantain, sausage and egg.</p>
            </div>

        </div>

        <a class="action" href="order.php">Place an Order</a>
    </div>

    <div class="section">

        <?php if ($role === 'Inputer'): ?>

            <h2>Data Entry</h2>
            <p>You can access the data-entry functions permitted for your account.</p>

        <?php elseif ($role === 'Manager'): ?>

            <h2>Manager Functions</h2>
            <p>You can create Inputer accounts.</p>
            <a class="action" href="add_inputer.php">Add Inputer</a>

        <?php elseif ($role === 'Admin'): ?>

            <h2>Admin Functions</h2>
            <p>You can delete Inputer accounts.</p>
            <a class="action" href="delete_inputer.php">Delete Inputer</a>

        <?php elseif ($role === 'Administrator'): ?>

            <h2>Administrator Functions</h2>
            <p>You can manage user accounts.</p>
            <a class="action" href="manage_users.php">Manage Users</a>

        <?php else: ?>

            <p>Your role is not recognized. Contact the system administrator.</p>

        <?php endif; ?>

        <a class="action logout" href="logout.php">Logout Securely</a>

    </div>

</main>
</div>

<footer>
    &copy; <?php echo date('Y'); ?> Rae Veloura Foods.
    Secure Login and User Management System.
</footer>

</body>
</html>