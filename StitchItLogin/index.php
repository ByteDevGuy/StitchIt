<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Stitch It - Handmade Crochet</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(#eee, #f4e3ff);
      color: #333;
    }

    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 6px 30px;
      background: #fff;
      border-bottom: 1px solid #ccc;
      position: sticky;
      top: 0;
      z-index: 1000;
      height: 60px;
    }

    .logo img {
      height: 38px;
    }

    nav {
      display: flex;
      gap: 12px;
    }

    nav a {
      text-decoration: none;
      padding: 6px 10px;
      color: #444;
      font-weight: 500;
      font-size: 14px;
      border-radius: 20px;
      transition: background 0.3s;
    }

    nav a:hover {
      background:rgba(229, 248, 252, 0.63);
    }

    .hero {
      position: relative;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      padding: 60px 50px 100px;
      flex-wrap: wrap;
      gap: 40px;
      height: 607px;
      color: #333;
      /*background-image: url('../Pictures/etona.jpg');*/
      background-size: cover;
      background-position: center;
    }

    .hero::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: inherit;
      background-size: cover;
      background-position: center;
      opacity: 0.3;
      z-index: 0;
    }

    .hero > * {
      position: relative;
      z-index: 1;
    }

    .hero-text {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 20px;
      max-width: 600px;
      justify-content: flex-start;
      color: #333;
      position: relative;
    }

    .hero-text .title {
      font-size: 48px;
      font-weight: 700;
      text-align: left;
      color: #333;
      margin-top: 68px;
    }

    .hero-text .description {
      font-size: 18px;
      text-align: left;
      color: #555;
    }

    .hero-text .cta-button {
      align-self: flex-start;
      padding: 15px 40px;
      background: rgb(209, 139, 186);
      border-radius: 25px;
      border: none;
      cursor: pointer;
      font-size: 18px;
      font-weight: 600;
      color: #fff;
      transition: background 0.3s;
      text-decoration: none;
    }

    .hero-text .cta-button:hover {
      background: #c59a8a;
    }

    .hero-image {
      flex: 1;
      height: 500px;
      position: relative;
    }

    .hero-image img {
      width: 100%;
      height: auto;
      object-fit: contain;
      transform: translateY(-50px);
      transition: transform 0.3s ease;
    }

    @media (max-width: 768px) {
      .hero {
        flex-direction: column;
        padding: 30px 20px 60px;
        height: auto;
      }

      .hero-text .title,
      .hero-text .description {
        text-align: center;
        margin-top: 20px;
      }

      .hero-text .cta-button {
        align-self: center;
        margin-top: 30px;
      }

      .hero-image {
        height: auto;
        width: 100%;
      }

      .hero-image img {
        transform: translateY(0);
        max-width: 100%;
        height: auto;
      }
    }

    .section-heading {
      text-align: center;
      font-size: 50px;
      margin: 60px auto 20px;
      color: #444;
      font-weight: 600;
      margin-top: 6%;
    }

    .carousel-container {
      position: relative;
      width: 90%;
      max-width: 1000px;
      margin: 0 auto 120px;
      overflow: hidden;
    }

    .carousel {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      gap: 20px;
      transition: transform 0.5s ease;
    }

    .image-container {
      width: 200px;
      height: 300px;
      border-radius: 15px;
      overflow: hidden;
      cursor: pointer;
    }

    .image-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      opacity: 0.4;
      transform: scale(0.8);
      transition: all 0.5s ease;
      border-radius: 15px;
      display: block;
    }

    .image-container.active img {
      opacity: 1;
      transform: scale(1.1);
      z-index: 2;
      box-shadow: 0 0 20px rgba(63, 55, 55, 0.437);
    }

    .category-label {
      margin-top: 20px;
      font-size: 16px;
      font-weight: 600;
      color: #444;
      text-align: center;
      user-select: none;
      display: none;
    }

    .image-container.active + .category-label {
      display: block;
    }

    .arrow {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255, 255, 255, 0.1);
      color: #333;
      font-size: 2rem;
      border: none;
      cursor: pointer;
      z-index: 3;
      padding: 10px;
      border-radius: 50%;
      transition: background 0.3s ease;
    }

    .arrow:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .arrow.left {
      left: 10px;
    }

    .arrow.right {
      right: 10px;
    }

    /* 🧶 Mid Footer Section */
    .crochet-info-section {
      width: 100%;
      background-color: #fdf6f0;
      padding: 60px 5% 40px;
      margin-top: 40px;
      border-top: 1px solid #e1dcd8;
      border-bottom: 1px solid #e1dcd8;
    }

    .info-columns {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 30px;
      max-width: 1200px;
      margin: auto;
    }

    .info-group {
      flex: 1 1 200px;
    }

    .info-group h4 {
      font-size: 16px;
      font-weight: 700;
      margin-bottom: 15px;
      color: #333;
    }

    .info-group ul {
      list-style: none;
      padding: 0;
    }

    .info-group ul li {
      margin-bottom: 10px;
    }

    .info-group ul li a {
      text-decoration: none;
      color: #555;
      font-size: 14px;
    }

    .info-group ul li a:hover {
      color: #000;
    }

    .newsletter-group p {
      font-size: 14px;
      color: #444;
      margin-bottom: 10px;
    }

    .newsletter-group input {
      width: 100%;
      padding: 8px 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-family: inherit;
      font-size: 14px;
    }

    .newsletter-group button {
      background-color: #d18bba;
      border: none;
      padding: 10px 20px;
      color: white;
      font-weight: 600;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .newsletter-group button:hover {
      background-color: #c07ca7;
    }

    @media (max-width: 768px) {
      .info-columns {
        flex-direction: column;
        gap: 40px;
      }
    }

    .footer-icons-wrapper {
      text-align: center;
      margin-top: 60px;
    }

    .footer-icons-wrapper p {
      font-size: 18px;
      font-weight: 600;
      color: #444;
      margin-bottom: 15px;
    }

    .footer-icons {
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    .footer-icons .icon-container {
      width: 40px;
      height: 40px;
      border-radius: 20%;
      overflow: hidden;
      border: 1px solid #ccc;
    }

    .footer-icons .icon-container img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    footer.footer {
      text-align: center;
      padding: 10px 0;
      background-color:rgb(230, 229, 229);
      color: #666;
      font-size: 14px;
      margin-top: 2%;
    }

    /* MODAL STYLES */
    .modal {
      display: none;
      position: fixed;
      z-index: 5000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 30px;
      border-radius: 10px;
      max-width: 600px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      font-size: 16px;
      line-height: 1.6;
      position: relative;
    }

    .close-btn {
      color: #aaa;
      position: absolute;
      right: 15px;
      top: 10px;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }

    .close-btn:hover {
      color: #000;
    }

    .info-columns.centered {
  justify-content: center;
  text-align: left;
  max-width: 600px;
  margin: 0 auto;
  padding-left: 120px; /* 🆕 This moves content slightly to the right */
}

nav a.active, nav a:hover {
        background-color: #f5c4e4;
        color: #fff;
    }

    
  </style>
</head>
<body>

<header>
  <div class="logo">
    <img src="Pictures/logo.png" alt="Stitch It Logo">
  </div>
  <nav>
    <a href="index.php" class="active">Home</a>
    <a href="Shop/Shop.php" >Shop</a>
    <a href="Customize/Customize.php">Customize</a>
    <a href="Cart/Cart.php">Cart</a>

    <?php if (isset($_SESSION['username'])): ?>
      <a href="Profile/userprofile.php">Profile</a>
      <a href="LoginSignup/Logout.php" onclick="confirmLogout(event)">Logout</a>
    <?php else: ?>
      <a href="LoginSignup/LoginSignup.php">Login / Sign Up</a>
    <?php endif; ?>
  </nav>
</header>

<section class="hero">
  <div class="hero-text">
    <div class="title">Turn your visions into reality with StitchIt!</div>
    <div class="description">
      Dream it, we crochet it! StitchIt takes your ideas and transforms them
      into cherished, handcrafted crochet treasures.
    </div>
    <a href="Shop/Shop.php" class="cta-button">Shop Now</a>
  </div>
  <div class="hero-image">
    <img src="Pictures/dolly.png" alt="Crocheted Dolly" />
  </div>
</section>

<br><br>
<br><br>


<h2 class="section-heading">WHAT TO OFFER?</h2>
<div class="carousel-container">
  <button class="arrow left">&#10094;</button>
  <div class="carousel" data-images='[
    {"src":"Pictures/plushies.jpg","label":"PLUSHIES"},
    {"src":"Pictures/tooop.jpg","label":"TOPS"},
    {"src":"Pictures/sskirt.webp","label":"SKIRTS"},
    {"src":"Pictures/bags.jpg","label":"BAGS"},
    {"src":"Pictures/swimwear.jpg","label":"SWIMWEAR"},
    {"src":"Pictures/beanies.jpg","label":"BEANIES"}
  ]'></div>
  <button class="arrow right">&#10095;</button>
</div>

<!-- 🧶 Mid Footer Section -->
<section class="crochet-info-section">
  <div class="info-columns centered">
    <!-- About Section -->
    <div class="info-group">
      <h4>ABOUT</h4>
      <ul>
        <li><a href="#" id="about-link">About StitchIt</a></li>
        <li><a href="#" id="artists-link">Crochet Artists</a></li>
        <li><a href="#" id="materials-link">Our Materials</a></li>

      </ul>
    </div>

    <!-- Support Section -->
    <div class="info-group">
      <h4>SUPPORT</h4>
      <ul>
        <li><a href="Contact/Contact.php">Contact Us</a></li>
        <li><a href="Customize/customize.php">Custom Orders</a></li>
        <li><a href="#" id="faq-link">FAQs</a></li>

      </ul>
    </div>
  </div>
</section>


<!-- Social Icons Section -->
<div class="footer-icons-wrapper">
  <p>Follow us here!</p>
  <div class="footer-icons">
    <a href="https://facebook.com" target="_blank" class="icon-container">
      <img src="Pictures/fb.png" alt="Facebook" />
    </a>
    <a href="https://instagram.com" target="_blank" class="icon-container">
      <img src="Pictures/ig.png" alt="Instagram" />
    </a>
    <a href="https://x.com" target="_blank" class="icon-container">
      <img src="Pictures/x.webp" alt="X" />
    </a>
    <a href="https://tiktok.com" target="_blank" class="icon-container">
      <img src="Pictures/tk.png" alt="TikTok" />
    </a>
  </div>
</div>

<!-- Footer -->
<footer class="footer">
  <div class="credits">© 2025 StitchIt Handmade Crochet</div>
</footer>

<!-- About StitchIt Modal -->
<div id="aboutModal" class="modal">
  <div class="modal-content">
    <span class="close-btn">&times;</span>
    <h2>About StitchIt</h2>
    <p>
      StitchIt is a creative haven for handcrafted crochet pieces made with love and care.
      From cozy plushies to stylish tops and accessories, each item is uniquely made to bring
      warmth and charm to your everyday life. Supporting StitchIt means supporting local artisans
      and sustainable, slow fashion.
    </p>
  </div>
</div>

<!-- Our Materials Modal -->
<div id="materialsModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" id="closeMaterials">&times;</span>
    <h2>Our Materials</h2>
    <p>
      At StitchIt, we take pride in using only high-quality yarns and sustainable materials to ensure each crochet piece is durable, soft, and ethically made. From cotton blends to eco-friendly fibers, we craft with care so you receive not just a product—but a lasting treasure.
    </p>
  </div>
</div>

<!-- FAQ Modal -->
<div id="faqModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" id="closeFaq">&times;</span>
    <h2>Frequently Asked Questions</h2>
    <ul style="padding-left: 20px; line-height: 1.8; list-style-type: disc;">
      <li><strong>Q:</strong> What materials do you use?<br><strong>A:</strong> We use high-quality yarn such as cotton, acrylic, and eco-friendly blends.</li>
      <li><strong>Q:</strong> How long does a custom order take?<br><strong>A:</strong> Typically 5–10 business days depending on complexity.</li>
      <li><strong>Q:</strong> Do you ship internationally?<br><strong>A:</strong> Currently, we only ship within the Philippines.</li>
      <li><strong>Q:</strong> Can I request a specific color or design?<br><strong>A:</strong> Yes! You can message us for custom requests via the Customize page.</li>
      <li><strong>Q:</strong> What is your return policy?<br><strong>A:</strong> We accept returns within 7 days of delivery for damaged or incorrect items.</li>
      <li><strong>Q:</strong> Are your products handmade?<br><strong>A:</strong> Absolutely! Each piece is carefully crocheted by our artists.</li>
      <li><strong>Q:</strong> How do I care for crochet items?<br><strong>A:</strong> Hand wash gently in cold water and lay flat to dry.</li>
      <li><strong>Q:</strong> Where are you based?<br><strong>A:</strong> We are proudly based in the Philippines and run by student artisans.</li>
    </ul>
  </div>
</div>

<script>
  const images = JSON.parse(document.querySelector('.carousel').dataset.images);
  let currentIndex = 0;
  const carousel = document.querySelector('.carousel');

  function renderCarousel() {
    carousel.innerHTML = '';

    const leftIndex = (currentIndex - 1 + images.length) % images.length;
    const centerIndex = currentIndex;
    const rightIndex = (currentIndex + 1) % images.length;

    const visibleIndices = [leftIndex, centerIndex, rightIndex];

    visibleIndices.forEach((i, idx) => {
      const container = document.createElement('div');
      container.style.display = 'flex';
      container.style.flexDirection = 'column';
      container.style.alignItems = 'center';

      const imgContainer = document.createElement('div');
      imgContainer.classList.add('image-container');
      if (idx === 1) imgContainer.classList.add('active');

      const img = document.createElement('img');
      img.src = images[i].src;

      const label = document.createElement('span');
      label.className = 'category-label';
      label.textContent = images[i].label;

      imgContainer.appendChild(img);
      container.appendChild(imgContainer);
      container.appendChild(label);

      carousel.appendChild(container);
    });
  }

  document.querySelector('.arrow.left').addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    renderCarousel();
  });

  document.querySelector('.arrow.right').addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % images.length;
    renderCarousel();
  });

  renderCarousel();

  // Modal Functionality
  const aboutLink = document.getElementById('about-link');
  const modal = document.getElementById('aboutModal');
  const closeBtn = modal.querySelector('.close-btn');

  aboutLink.addEventListener('click', (e) => {
    e.preventDefault();
    modal.style.display = 'block';
  });

  closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  window.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });
</script>

<!-- Crochet Artists Modal -->
<div id="artistsModal" class="modal">
  <div class="modal-content">
    <span class="close-btn" id="closeArtists">&times;</span>
    <h2>Meet Our Crochet Artists</h2>
    <p>We are BS in Information Technology students from section <strong>BSIT-2D</strong> who created StitchIt as a creative crochet concept. Meet our team:</p>
    <ul style="margin-top: 15px; padding-left: 20px; line-height: 1.8;">
      <li>Brul, Kationa E.</li>
      <li>Caraan, Aliyah Jane P.</li>
      <li>Daella, Harvey B.</li>
      <li>Malabanan, Danah Mae</li>
      <li>Lorenzo, Nash Richlei</li>
      <li>Pontipedra, Karl Micheal Z.</li>
      <li>Romanes, Wayne Gerard B.</li>
      <li>Tallud, Hazel Ann</li>
    </ul>
  </div>
</div>

<script>
  // Artists Modal
  const artistsLink = document.getElementById('artists-link');
  const artistsModal = document.getElementById('artistsModal');
  const closeArtists = document.getElementById('closeArtists');

  artistsLink.addEventListener('click', (e) => {
    e.preventDefault();
    artistsModal.style.display = 'block';
  });

  closeArtists.addEventListener('click', () => {
    artistsModal.style.display = 'none';
  });

  window.addEventListener('click', (e) => {
    if (e.target === artistsModal) {
      artistsModal.style.display = 'none';
    }
  });

    // Materials Modal
  const materialsLink = document.getElementById('materials-link');
  const materialsModal = document.getElementById('materialsModal');
  const closeMaterials = document.getElementById('closeMaterials');

  materialsLink.addEventListener('click', (e) => {
    e.preventDefault();
    materialsModal.style.display = 'block';
  });

  closeMaterials.addEventListener('click', () => {
    materialsModal.style.display = 'none';
  });

  window.addEventListener('click', (e) => {
    if (e.target === materialsModal) {
      materialsModal.style.display = 'none';
    }
  });
  // FAQ Modal
  const faqLink = document.getElementById('faq-link');
  const faqModal = document.getElementById('faqModal');
  const closeFaq = document.getElementById('closeFaq');

  faqLink.addEventListener('click', (e) => {
    e.preventDefault();
    faqModal.style.display = 'block';
  });

  closeFaq.addEventListener('click', () => {
    faqModal.style.display = 'none';
  });

  window.addEventListener('click', (e) => {
    if (e.target === faqModal) {
      faqModal.style.display = 'none';
    }
  });

</script>


<script src="LogoutConfirmation.js" defer></script>
</body>
</html>
