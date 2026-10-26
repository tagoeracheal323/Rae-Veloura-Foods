<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">

    <title>Rae Veloura Foods | Welcome</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background: #ffffff !important;
            background-color: #ffffff !important;
            background-image: none !important;
            color-scheme: light !important;
            color: #10294b;
            font-family: Arial, sans-serif;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 22px 8%;
        }

        .brand {
            color: #b08a2e;
            font-family: Georgia, serif;
            font-size: 25px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .nav-link {
            color: #10294b;
            text-decoration: none;
            font-size: 14px;
        }

        .hero {
            min-height: 78vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
            text-align: center;
        }

        .hero-content {
            max-width: 720px;
        }

        .eyebrow {
            color: #10294b;
            font-size: 12px;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 22px;
        }

        h1 {
            color: #10294b;
            font-family: Georgia, serif;
            font-size: clamp(36px, 6vw, 62px);
            line-height: 1.2;
            margin-bottom: 22px;
        }

        .description {
            max-width: 560px;
            margin: 0 auto 32px;
            color: #59677c;
            font-size: 16px;
            line-height: 1.9;
        }

        .buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .button {
            display: inline-block;
            min-width: 155px;
            padding: 15px 25px;
            border: 0;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .primary-button {
            background: #10294b;
            color: #e6c66b;
        }

        .primary-button:hover {
            background: #183b69;
            transform: translateY(-2px);
        }

        .secondary-button {
            color: #10294b;
            background: #ffffff;
        }

        .secondary-button:hover {
            background: #f7f4eb;
            transform: translateY(-2px);
        }

        footer {
            padding: 22px;
            text-align: center;
            color: #10294b;
            font-size: 12px;
            letter-spacing: 1px;
        }

        @media (max-width: 500px) {
            header {
                padding: 20px;
            }

            .brand {
                font-size: 19px;
            }

            .hero {
                min-height: 75vh;
            }

            .description {
                font-size: 14px;
            }

            .buttons {
                flex-direction: column;
                align-items: center;
            }

            .button {
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
    <link rel="stylesheet" href="styles.css?v=20261026-4">
</head>

<body>

    <header>
        <div class="brand">RAE VELOURA FOODS</div>

        <a href="login.php" class="nav-link">My Account</a>
    </header>

    <main class="hero">
        <div class="hero-content">

            <p class="eyebrow">Welcome to our world</p>

            <h1>Welcome to<br><span class="brand-highlight">Rae Veloura Foods</span></h1>

            <p class="description">
                Elegance in Every Bite. Welcome to Rae Veloura Foods,
                where great taste meets a touch of sophistication.
                Sign in to your account or create one to continue.
            </p>

            <div class="buttons">
                <a href="login.php" class="button primary-button">
                    Sign In
                </a>

                <a href="register.php" class="button secondary-button">
                    Create Account
                </a>
            </div>

        </div>
    </main>

    <footer>
        &copy; <?php echo date("Y"); ?> Rae Veloura Foods. All rights reserved.
    </footer>

</body>
</html>