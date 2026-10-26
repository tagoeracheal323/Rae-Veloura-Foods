<?php
session_start();

$message = "";
$status = "";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (
    !isset($_SESSION["otp_hash"], $_SESSION["otp_expiry"]) ||
    time() > $_SESSION["otp_expiry"]
) {
    $message = "Your OTP has expired. Please request a new code.";
    $status = "error";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $entered_otp = trim($_POST["otp"] ?? "");

    if (
        !isset($_SESSION["otp_hash"], $_SESSION["otp_expiry"]) ||
        time() > $_SESSION["otp_expiry"]
    ) {
        $message = "Your OTP has expired. Please request a new code.";
        $status = "error";

        unset($_SESSION["otp_hash"], $_SESSION["otp_expiry"]);

    } elseif (!preg_match('/^\d{6}$/', $entered_otp)) {

        $message = "Enter a valid six-digit OTP.";
        $status = "error";

    } elseif (password_verify($entered_otp, $_SESSION["otp_hash"])) {

        $_SESSION["otp_verified"] = true;

        unset($_SESSION["otp_hash"], $_SESSION["otp_expiry"]);

        header("Location: dashboard.php");
        exit();

    } else {

        $message = "That code does not match. Use the code from your most recent verification email; each new login code replaces the previous one.";
        $status = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">

    <title>Verify OTP | Rae Veloura Foods</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 24px;
            background: #ffffff !important;
            background-color: #ffffff !important;
            background-image: none !important;
            color-scheme: light !important;
            color: #10294b;
            font-family: Arial, sans-serif;
        }

        .verification-container {
            width: 100%;
            max-width: 560px;
            min-height: 0;
            padding: 50px 6%;
            margin: 0 auto;

            display: flex;
            flex-direction: column;
            justify-center: center;
            align-items: center;

            text-align: center;
            background: #ffffff;
            border: 0;
            border-radius: 16px;
            box-shadow: 0 12px 36px rgba(16, 41, 75, 0.1);
        }

        .brand-name {
            margin: 0;
            color: #b08a2e;
            font-family: Georgia, serif;
            font-size: 27px;
        }

        .tagline {
            margin: 10px 0 30px;
            color: #10294b;
            font-size: 12px;
            letter-spacing: 1.5px;
        }

        .verification-icon {
            width: 65px;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 0;
            border-radius: 50%;
            color: #10294b;
            font-size: 30px;
        }

        h2 {
            margin-bottom: 12px;
            color: #10294b;
            font-size: 23px;
        }

        .description {
            margin-bottom: 25px;
            color: #10294b;
            font-size: 14px;
            line-height: 1.7;
        }

        label {
            display: block;
            margin-bottom: 12px;
            color: #10294b;
            font-size: 14px;
            text-align: left;
        }

        .otp-input {
            width: 100%;
            padding: 15px 10px;
            border: 0;
            border-radius: 7px;
            background: #ffffff;
            color: #10294b;
            font-size: 25px;
            letter-spacing: 10px;
            text-align: center;
            outline: none;
        }

        .otp-input:focus {
            box-shadow: 0 0 6px rgba(176, 138, 46, 0.25);
        }

        .verify-button {
            width: 100%;
            margin-top: 22px;
            padding: 14px;
            border: none;
            border-radius: 7px;
            background: #10294b;
            color: #e6c66b;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .verify-button:hover {
            background: #183b69;
        }

        .message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 7px;
            font-size: 14px;
            line-height: 1.5;
        }

        .error {
            border: 0;
            background: #fff9e9;
            color: #10294b;
        }

        .footer {
            margin-top: 28px;
            color: #10294b;
            font-size: 11px;
            letter-spacing: 1px;
        }

        @media (max-width: 480px) {
            .verification-container {
                padding: 32px 22px;
            }

            .brand-name {
                font-size: 24px;
            }

            .otp-input {
                letter-spacing: 7px;
            }
        }
    </style>
    <link rel="stylesheet" href="styles.css?v=20261026-4">
</head>

<body>

    <main class="verification-container">

        <h1 class="brand-name">Rae Veloura Foods</h1>

        <p class="tagline">ELEGANCE IN EVERY BITE</p>

        <div class="verification-icon" aria-hidden="true">
            &#10003;
        </div>

        <h2>Two-Factor Authentication</h2>

        <p class="description">
            Enter the six-digit code sent to your account email to verify your
            identity and securely access your account. The latest code is valid
            for five minutes; requesting a new code replaces the previous one.
        </p>

        <?php if (isset($_GET["sent"]) && $_GET["sent"] === "1"): ?>
            <p class="description" role="status">
                Your verification email was sent. Use the most recent code in that email.
            </p>
        <?php endif; ?>

        <form method="POST" action="">

            <label for="otp">One-Time Password (OTP)</label>

            <input
                type="text"
                id="otp"
                name="otp"
                class="otp-input"
                inputmode="numeric"
                pattern="[0-9]{6}"
                minlength="6"
                maxlength="6"
                placeholder="000000"
                autocomplete="one-time-code"
                aria-label="Enter your six-digit OTP"
                required
            >

            <button type="submit" class="verify-button">
                Verify &amp; Continue
            </button>

        </form>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo htmlspecialchars($status); ?>">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <div class="footer">
            &copy; <?php echo date("Y"); ?> RAE VELOURA FOODS
        </div>

    </main>

</body>

</html>