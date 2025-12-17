<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/authform.css">
    <style>
        .form-error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <main class="container">
        <div class="register-card">

            <div class="register-image">
                <img src="images/penguin.gif" alt="No images available">
                <h2>Welcome Back</h2>
                <p>Login to access your account and explore listings.</p>
            </div>

            <div class="register-form">
                <h2>Login</h2>

                <form method="POST" action="handlers/login_handler.php">


                    <?php if (!empty($_SESSION['login_errors'])) : ?>
                        <div class="form-error">
                            <?php
                            echo implode('<br>', array_map('htmlspecialchars', $_SESSION['login_errors']));
                            unset($_SESSION['login_errors']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>

                    <button type="submit" class="form-button">Login</button>

                    <p style="margin-top:10px;">
                        Don't have an account? <a href="register.php">Register here</a>
                    </p>
                </form>
            </div>

        </div>
    </main>

    <footer>
        <p>Ver 1.1.0</p>
    </footer>
</body>

</html>