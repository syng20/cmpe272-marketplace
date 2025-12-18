<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
  <link href="css/styles.css" rel="stylesheet">
  <link href="css/carousel.css" rel="stylesheet">

</head>

<body>
  <header>
    <?php include 'navbar.php'; ?>
  </header>

  <section class="carousel">
    <div class="carousel-track">

      <div class="carousel-slide active"
        style="background-image: url('/images/sjsu.jpg');">
        <div class="carousel-content">
          <h3>Welcome to Spartan Market</h3>
          <p>Buy your Spartan merch securely with ease.</p>
        </div>
      </div>

      <div class="carousel-slide"
        style="background-image: url('/images/bakery.jpg');">
        <div class="carousel-content">
          <h3>Juso Bakery</h3>
          <p>Get fresh baked goods straight to your door.</p>
        </div>
      </div>

      <div class="carousel-slide"
        style="background-image: url('/images/apiary.jpg');">
        <div class="carousel-content">
          <h3>New Leaf Apiary</h3>
          <p>Fresh honey sourced locally.</p>
        </div>
      </div>

      <div class="carousel-slide"
        style="background-image: url('/images/watches.jpg');">
        <div class="carousel-content">
          <h3>Right Twice</h3>
          <p>One stop shop for all your watch needs.</p>
        </div>
      </div>
    </div>

    <div class="carousel-controls">
      <button id="prevBtn">❮</button>
      <button id="nextBtn">❯</button>
    </div>
  </section>
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
        <form method="POST">
          <button type="submit" class="button">
            <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h2>
          </button>
        </form>
      </div>
      <div class="buttonholder">
        <form method="POST" action="handlers/logout.php">
          <button type="submit" class="button">
            <h2>Log Out</h2>
          </button>
        </form>
      </div>
    <?php endif; ?>
  </main>



  <script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    let autoSlideInterval;

    function showSlide(index) {
      slides.forEach(slide => slide.classList.remove('active'));
      slides[index].classList.add('active');
    }

    function nextSlide() {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    }

    function prevSlide() {
      currentSlide = (currentSlide - 1 + slides.length) % slides.length;
      showSlide(currentSlide);
    }

    function startAutoSlide() {
      autoSlideInterval = setInterval(nextSlide, 5000);
    }

    function resetAutoSlide() {
      clearInterval(autoSlideInterval);
      startAutoSlide();
    }

    document.getElementById('prevBtn').addEventListener('click', () => {
      prevSlide();
      resetAutoSlide();
    });

    document.getElementById('nextBtn').addEventListener('click', () => {
      nextSlide();
      resetAutoSlide();
    });

    startAutoSlide();
  </script>

  <footer>
    <p>Ver 1.1.0</p>
  </footer>
</body>

</html>