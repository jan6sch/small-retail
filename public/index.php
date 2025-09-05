<?php
setlocale(LC_TIME, "de_DE.UTF-8");
?>

<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zeit für Wolle</title>
</head>
<?php
  include __DIR__ . '/../includes/stylesheets.html';
?>


</head>

<body>

<?php include __DIR__ . '/../includes/nav-include.php'; ?>




  <main>
    <section id="about" class="container section">

      <div class="about-container">
  <img src="#" alt="Bild vom Laden">
  <div class="about-text">
    <h2>Über uns</h2>
    <p>
      Schon seit meiner Jugend hat mich das Stricken begleitet und begeistert. Im Laufe der Zeit wuchs bei mir
            allmählich der Wunsch eine größere Herausforderung um das Thema „Wolle“ zu suchen. Als sich dann für mich
            die Möglichkeit geboten hat im elterlichen Geschäft einen eigenen Woll-Laden aufzubauen, wurde aus Wunsch
            Wirklichkeit.

            Mit meinem Woll-Laden möchte ich meine Freude am Stricken und Häkeln gern mit anderen Menschen teilen, sowie
            neue Begeisterte gewinnen, an die ich meine Erfahrungen weitergeben darf. Ich lade Sie zum Stöbern, Shoppen
            und Austausch mit Gleichgesinnten in einer entspannten Atmosphäre ein, die für eine kreative Auszeit von
            Ihrem Alltag sorgen soll.
    </p>
  </div>
</div>

    </section>








    <?php setlocale(LC_TIME, "de_DE.UTF-8"); ?>
<section id="news" class="container section">
  <div class="news-flexbox">

    <!-- Linke Seite: News -->
    <div class="news-column">
      <div class="news-header">
        <h2>Neues</h2>
        <div class="news-date">
          <?php echo strftime("%a %d.%m.%Y", strtotime("today"));
 ?>
        </div>
      </div>

      <div class="news-list">
        <?php include __DIR__ . '/../includes/news-include.php'; ?>
      </div>
    </div>

    <!-- Rechte Seite: Slider -->
    <div class="news-slider-wrapper">
      <div class="news-slider-container">
        <?php include __DIR__ . '/../includes/news_slider-include.php'; ?>
      </div>
    </div>

  </div>
</section>



    <section id="brands" class="container section">
      <div class="marken-header">
      <h2>Unsere Marken</h2>
      <a href="../public/marken.php" class="marken-link">Alle Marken anzeigen</a>
      </div>
      <div class="marken">
    <?php include __DIR__ . '/../includes/marken-include.php'; ?>
  </div> 
    </section>

    <section id="contact" class="container section">
  <h2>Kontakt</h2>
  <div class="contact-layout">
    <!-- Linke Seite: Formular -->
    <div class="contact-left">
      <div class="contact-box">
        <h3>Schreib uns <i class="fa-solid fa-envelope"></i></h3>
        <form action="send_mail.php" method="post" class="kontakt-form">
          <label for="name">Dein Name:</label>
          <input type="text" id="name" name="name" maxlength="50" required>

          <label for="email">Deine E-Mail:</label>
          <input type="email" id="email" name="email" maxlength="100" required>

          <label for="title">Titel:</label>
          <input type="text" id="title" name="title" maxlength="50" required>

          <label for="message">Nachricht:</label>
          <textarea id="message" name="message" rows="6" maxlength="550" required></textarea>

          <button type="submit">Absenden</button>
        </form>
      </div>
    </div>

    <!-- Rechte Seite: Adresse + Telefon + Öffnungszeiten -->
    <div class="contact-right">
      <div class="contact-box">
        <h3>Adresse <i class="fa-solid fa-location-dot"></i></h3>
        <p>
          Graf-Heinrich-Straße 4,<br>
          57627 Hachenburg
        </p>
        <a href="https://www.google.com/maps/place/Zeit+f%C3%BCr+Wolle/@50.6624549,7.8209209,17z/data=!3m1!4b1!4m6!3m5!1s0x47be9d00721a7dc9:0x8eb828c58194413!8m2!3d50.6624549!4d7.8209209!16s%2Fg%2F11m718symv?entry=ttu&g_ep=EgoyMDI1MDgxOS4wIKXMDSoASAFQAw%3D%3D" target="_blank">
          Auf Google Maps ansehen
        </a>
      </div>

      <div class="contact-box">
  <h3>Telefon <i class="fa-solid fa-phone-volume"></i></h3>
  <p>
    <a href="tel:026629493311">02662 9493311</a><br>
    <small>zu den Öffnungszeiten erreichbar</small>
  </p>
</div>


      <?php include __DIR__ . '/../includes/oeffnungszeiten-include.php'; ?>
    </div>
  </div>
</section>




  </main>

  <footer>
    <div class="container">
      <div class="mobile-socials">
        <?php 
          include __DIR__ . '/../includes/socials-include.php'; 
          if (isset($mobileSocials)) {
            echo $mobileSocials;
          }
        ?>
      </div>
      <p><a href="impressum.html">Impressum</a></p>
    </div>
  </footer>
  
  <script src="script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="swiper.js"></script>
</body>

</html>