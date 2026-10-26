<?php
session_start();

require_once 'db.php';
require_once 'email_config.php';

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("A valid email address was not found. Please log in again.");
}

if (
    MAIL_USERNAME === 'yourgmail@gmail.com' ||
    MAIL_PASSWORD === 'YOUR_16_CHARACTER_APP_PASSWORD'
) {
    http_response_code(503);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="color-scheme" content="only light">
        <title>Email Setup Required | Rae Veloura Foods</title>
        <style>
            * { box-sizing: border-box; }
            html, body { min-height: 100%; }
            body {
                margin: 0;
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 24px;
                background: #ffffff;
                color: #10294b;
                font-family: Arial, sans-serif;
            }
            main {
                width: min(100%, 560px);
                padding: 40px;
                border-radius: 16px;
                background: #ffffff;
                box-shadow: 0 12px 36px rgba(16, 41, 75, 0.1);
            }
            h1 { color: #b08a2e; font-family: Georgia, serif; }
            p { line-height: 1.7; }
            a {
                display: inline-block;
                margin-top: 12px;
                padding: 12px 20px;
                border-radius: 7px;
                background: #10294b;
                color: #e6c66b;
                text-decoration: none;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <main>
            <h1>Rae Veloura Foods</h1>
            <h2>Email setup required</h2>
            <p>The login was accepted, but OTP email delivery is not configured yet.</p>
            <p>Update <strong>email_config.php</strong> with the Gmail address that sends OTPs and its Google App Password. Do not share the App Password in chat or publish it.</p>
            <p>After saving the correct settings, sign in again to request a new OTP.</p>
            <a href="login.php">Return to Sign In</a>
        </main>
    </body>
    </html>
    <?php
    exit;
}

// Generate a random six-digit OTP.
$otp = (string) random_int(100000, 999999);

// Store the OTP hash and expiry in the session.
$_SESSION['otp_hash'] = password_hash($otp, PASSWORD_DEFAULT);
$_SESSION['otp_expiry'] = time() + 300;
$_SESSION['otp_verified'] = false;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = MAIL_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = MAIL_USERNAME;
    $mail->Password = MAIL_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = MAIL_PORT;
    $mail->Timeout = 15;

    $mail->CharSet = 'UTF-8';
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your Rae Veloura Foods Verification Code';

    $safeOtp = htmlspecialchars($otp, ENT_QUOTES, 'UTF-8');

    $mail->Body = '
        <div style="font-family:Arial,sans-serif;max-width:520px;
                    margin:auto;padding:30px;border:1px solid #eee;
                    border-radius:12px;color:#10294b;">
            <h1 style="color:#b08a2e;text-align:center;">
                Rae Veloura Foods
            </h1>
            <p style="text-align:center;">ELEGANCE IN EVERY BITE</p>
            <h2>Verify Your Identity</h2>
            <p>Your verification code is:</p>
            <div style="background:#fff8e8;padding:20px;
                        text-align:center;font-size:32px;
                        font-weight:bold;letter-spacing:8px;">
                ' . $safeOtp . '
            </div>
            <p>This code expires in five minutes.</p>
            <p>If you did not request this code, you can ignore this email.</p>
        </div>
    ';

    $mail->AltBody =
        "Your Rae Veloura Foods verification code is: $otp. " .
        "It expires in five minutes.";

    $mail->send();

    // Do not display the OTP on the webpage.
    header("Location: verify_otp.php?sent=1");
    exit;

} catch (\Throwable $e) {
    error_log("Rae Veloura Foods email error: " . $e->getMessage());
    if ($mail->ErrorInfo !== '') {
        error_log("Rae Veloura Foods mailer diagnostic: " . $mail->ErrorInfo);
    }

    $diagnostic = $mail->ErrorInfo ?: $e->getMessage();
    $isDnsFailure = stripos($diagnostic, 'getaddrinfo') !== false
        || stripos($diagnostic, 'no such host') !== false;

    http_response_code(500);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="color-scheme" content="only light">
        <title>OTP Delivery Failed | Rae Veloura Foods</title>
        <style>
            * { box-sizing: border-box; }
            html, body { min-height: 100%; }
            body {
                margin: 0;
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 24px;
                background: #ffffff;
                color: #10294b;
                font-family: Arial, sans-serif;
            }
            main {
                width: min(100%, 560px);
                padding: 40px;
                border-radius: 16px;
                background: #ffffff;
                box-shadow: 0 12px 36px rgba(16, 41, 75, 0.1);
            }
            h1 { color: #b08a2e; font-family: Georgia, serif; }
            p { line-height: 1.7; }
            a {
                display: inline-block;
                margin-top: 12px;
                padding: 12px 20px;
                border-radius: 7px;
                background: #10294b;
                color: #e6c66b;
                text-decoration: none;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <main>
            <h1>Rae Veloura Foods</h1>
            <h2>We couldn't send your verification code</h2>
            <?php if ($isDnsFailure): ?>
                <p>This computer could not find Gmail's mail server address, so the verification email was not sent. Check that this computer has internet access and working DNS, then try again. This error is not caused by your OTP or App Password.</p>
            <?php else: ?>
                <p>Check the sender's Gmail App Password and PHP's OpenSSL certificate settings. The mail server reported:</p>
            <?php endif; ?>
            <p><strong><?php echo htmlspecialchars($diagnostic, ENT_QUOTES, 'UTF-8'); ?></strong></p>
            <p><a href="check_php.php" style="color:#10294b;background:#ffffff;padding:0;">Check PHP mail diagnostics</a></p>
            <p>Once the mail settings are fixed, sign in again to request a new code.</p>
            <a href="login.php">Return to Sign In</a>
        </main>
    </body>
    </html>
    <?php
}