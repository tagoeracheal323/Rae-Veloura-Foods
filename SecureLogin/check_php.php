<?php
$iniFile = php_ini_loaded_file();
$certificateFile = ini_get('openssl.cafile');
$certificatePath = ini_get('openssl.capath');
$opensslLoaded = extension_loaded('openssl');
$certificateFileReadable = $certificateFile !== ''
    && is_file($certificateFile)
    && is_readable($certificateFile);
$certificatePathReadable = $certificatePath !== ''
    && is_dir($certificatePath)
    && is_readable($certificatePath);

function checkValue(bool $isReady): string
{
    return $isReady ? 'Available' : 'Not configured or unavailable';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="only light">
    <title>PHP Mail Diagnostics | Rae Veloura Foods</title>
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
            width: min(100%, 700px);
            padding: 36px;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 12px 36px rgba(16, 41, 75, 0.1);
        }
        h1 { color: #b08a2e; font-family: Georgia, serif; }
        p { line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; vertical-align: top; }
        th { width: 38%; color: #b08a2e; }
        td { color: #10294b; overflow-wrap: anywhere; }
        tr:nth-child(even) { background: #f3f6fa; }
        .note { margin-top: 20px; }
        a { color: #10294b; font-weight: bold; }
    </style>
</head>
<body>
    <main>
        <h1>Rae Veloura Foods</h1>
        <h2>PHP mail diagnostics</h2>
        <p>This page checks PHP's mail-related TLS setup. It does not display or read your SMTP password.</p>
        <table>
            <tr>
                <th>PHP version</th>
                <td><?php echo htmlspecialchars(PHP_VERSION, ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Loaded PHP configuration</th>
                <td><?php echo htmlspecialchars($iniFile ?: 'Not reported', ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>OpenSSL extension</th>
                <td><?php echo checkValue($opensslLoaded); ?></td>
            </tr>
            <tr>
                <th>Configured certificate file</th>
                <td><?php echo htmlspecialchars($certificateFile ?: 'Not set', ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Certificate file readable</th>
                <td><?php echo checkValue($certificateFileReadable); ?></td>
            </tr>
            <tr>
                <th>Configured certificate directory</th>
                <td><?php echo htmlspecialchars($certificatePath ?: 'Not set', ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Certificate directory readable</th>
                <td><?php echo checkValue($certificatePathReadable); ?></td>
            </tr>
        </table>
        <p class="note">If OpenSSL is unavailable, enable the OpenSSL extension in the loaded php.ini and restart Apache. If Gmail reports authentication failure, use a Google App Password for the sending account, not its normal password.</p>
        <p><a href="login.php">Return to Sign In</a></p>
    </main>
</body>
</html>
