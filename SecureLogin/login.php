<?php
session_start();

require_once 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $sql = "SELECT ID, Username, Email, Password, role
            FROM users
            WHERE Email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user["Password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["ID"];
            $_SESSION["username"] = $user["Username"];
            $_SESSION["email"] = $user["Email"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["otp_verified"] = false;

            header("Location: send_otp.php");
            exit();
        } else {
            $message = "Invalid email or password.";
        }
    } else {
        $message = "Invalid email or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">

    <title>Login | Rae Veloura Foods</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            background: #ffffff !important;
            background-color: #ffffff !important;
            background-image: none !important;
            color-scheme: light !important;
            font-family: Arial, sans-serif;
            color: #10294b;
        }

        .login-container {
            width: 100%;
            max-width: 560px;
            margin: 0 auto;
            padding: 48px 6%;
            background: #ffffff;
            border: 0;
            border-radius: 16px;
            box-shadow: 0 12px 36px rgba(16, 41, 75, 0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .logo {
            display: block;
            width: 170px;
            height: 170px;
            object-fit: contain;
            margin: 0 auto 18px;
            border-radius: 12px;
        }

        .brand-name {
            margin: 0;
            color: #b08a2e;
            font-family: Georgia, serif;
            font-size: 40px;
            letter-spacing: 2px;
        }

        .tagline {
            margin: 12px 0 14px;
            color: #10294b;
            font-size: 18px;
        }

        h2 {
            color: #10294b;
            font-size: 32px;
            margin-bottom: 12px;
        }

        .description {
            color: #10294b;
            font-size: 18px;
            margin-bottom: 32px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 12px;
            color: #10294b;
            font-size: 16px;
        }

        input {
            width: 100%;
            padding: 18px;
            border: 0;
            border-radius: 8px;
            background: #ffffff;
            color: #10294b;
            font-size: 18px;
            outline: none;
        }

        input::placeholder {
            color: #738096;
        }

        input:focus {
            box-shadow: 0 0 5px rgba(176, 138, 46, 0.25);
        }

        .login-button {
            width: 100%;
            padding: 19px;
            margin-top: 12px;
            border: none;
            border-radius: 7px;
            background: #10294b;
            color: #e6c66b;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-button:hover {
            background: #183b69;
            transform: translateY(-1px);
        }

        .message {
            padding: 11px;
            margin-bottom: 18px;
            border: 0;
            border-radius: 6px;
            background: #fff9e9;
            color: #10294b;
            font-size: 14px;
        }

        .register-text {
            margin-top: 28px;
            color: #10294b;
            font-size: 17px;
        }

        .register-link {
            color: #10294b;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .footer {
            margin-top: 28px;
            color: #10294b;
            font-size: 11px;
            letter-spacing: 1px;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 32px 22px;
            }

            .brand-name {
                font-size: 24px;
            }
        }
    </style>
    <link rel="stylesheet" href="styles.css?v=20261026-4">
</head>

<body>

    <div class="login-container">

        <h1 class="brand-name">Rae Veloura Foods</h1>

        <p class="tagline">Elegance in Every Bite</p>

        <h2>Welcome Back</h2>

        <p class="description">
            Sign in to access your account.
        </p>

        <?php if (!empty($message)): ?>
            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">

            <div class="form-group">
                <label for="email">Email Address</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button
                type="submit"
                name="login"
                class="login-button"
            >
                Sign In
            </button>

        </form>

        <p class="register-text">
            Don't have an account?
            <a href="register.php" class="register-link">
                Create Account
            </a>
        </p>

        <div class="footer">
            &copy; <?php echo date("Y"); ?> RAE VELOURA FOODS
        </div>

    </div>

</body>
</html>