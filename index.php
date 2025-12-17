<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
  <link href="css/styles.css" rel="stylesheet">
</head>

<body>
  <header>
    <?php include 'navbar.php'; ?>
  </header>

  <!-- box selection -->
  <main id="homebuttoncontainer">
    <!-- Logged Out View -->
    <?php if (empty($_SESSION['user'])) : ?>
      <div class="buttonholder">
        <a href="register.php"><button class="button">
            <h2>New User</h2>
          </button></a>
      </div>
      <div class="buttonholder">
        <a href="login.php"><button class="button">
            <h2>Log In</h2>
          </button></a>
      </div>
    <!-- Logged In View -->
    <?php else: ?>
      <div class="buttonholder">
        <form method="POST" action="handlers/logout.php">
          <button type="submit" class="button">
            <h2>Log Out</h2>
          </button>
        </form>
      </div>
      <div class="buttonholder">
        <p>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</p>
      </div>
    <?php endif; ?>
  </main>


  <footer>
    <p>Ver 1.1.0</p>
  </footer>

</body>

</html>