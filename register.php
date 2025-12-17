<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
                <img src="images/fatcat.gif" alt="No images available">
                <h2>Join the Marketplace</h2>
                <p>Buy, sell, and explore listings from all websites.</p>
            </div>

            <div class="register-form">
                <h2>Create Account</h2>

                <form method="POST" action="handlers/register_handler.php">



                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>

                    <?php if (!empty($_SESSION['register_errors'])) : ?>
                        <div class="form-error">
                            <?php
                            echo implode('<br>', array_map('htmlspecialchars', $_SESSION['register_errors']));
                            unset($_SESSION['register_errors']);
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['register_success'])) : ?>
                        <div class="form-success" style ="color: green; margin-bottom: 10px; font-weight: bold;">
                            Registration successful! You can now log in.
                        </div>
                        <?php unset($_SESSION['register_success']); ?>
                    <?php endif; ?>
                    <button type="submit" class="form-button">Register</button>
                </form>
            </div>

        </div>
    </main>
    <footer>
        <p>Ver 1.1.0</p>
    </footer>
</body>

</html>