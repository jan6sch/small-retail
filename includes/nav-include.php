<header>
  <div class="container">
    <a href="index.php" class="logo-link">
    <div class="logo">Zeit für Wolle</div></a>

    <nav id="nav">
      <div class="nav-links">
        <a href="../public/ueber-uns" data-target="about">Über uns</a>
<a href="../public/news" data-target="news">Was gibt's Neues</a>
<a href="../public/marken" data-target="brands">Unsere Marken</a>
<a href="../public/kontakt" data-target="contact">Kontakt</a>

      </div>

      <div class="mobile-socials">
        <?php 
          include __DIR__ . '/../includes/socials-include.php'; 
          if (isset($mobileSocials)) {
            echo $mobileSocials;
          }
        ?>
      </div>
    </nav>

    <div class="hamburger" onclick="toggleMenu(this)">
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
</header>
