<?php
// DB-Verbindung laden
include __DIR__ . '/../includes/datenbank.php';

// exakt dieselbe Abfrage wie in marken-include.php
$sql = "SELECT id, name, beschreibung, logo_pfad, reihenfolge 
        FROM marken 
        WHERE wichtigkeit = 1 
        ORDER BY reihenfolge ASC";
$stmt = $pdo->query($sql);
$marken = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Unsere Marken</title>
  <?php include __DIR__ . '/../includes/stylesheets.html'; ?>


</head>
<body>
<header>
  <div class="container">
    <a href="index.php" class="logo-link">
    <div class="logo">Zeit für Wolle</div></a>

    <nav id="nav">
      <div class="nav-links">
        



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

    
    
  </div>
</header>
<main class="mkp-container">
  <h1 class="mkp-heading">Unsere Marken</h1>
  <section class="mkp-list" aria-label="Markenliste">
    <?php if (empty($marken)): ?>
      <p class="mkp-empty">Derzeit sind keine Marken verfügbar.</p>
    <?php else: ?>
      <?php foreach ($marken as $m): ?>
        <article id="marke-<?= (int)$m['id'] ?>" class="mkp-item" tabindex="-1">
          <div class="mkp-logo">
            <img src="../db_bilder/<?= htmlspecialchars(ltrim($m['logo_pfad'], '/')) ?>"
                 alt="Logo von <?= htmlspecialchars($m['name']) ?>" loading="lazy">
          </div>
          <div class="mkp-content">
            <h2 class="mkp-title"><?= htmlspecialchars($m['name']) ?></h2>
            <div class="mkp-desc"><?= nl2br(htmlspecialchars($m['beschreibung'])) ?></div>
          </div>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
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
<script>
window.addEventListener('load', () => {
  const params = new URLSearchParams(window.location.search);
  const id = params.get('marke');
  if (!id) return;

  const el = document.getElementById('marke-' + id);
  if (!el) return;

  const headerHeight = document.querySelector('header')?.offsetHeight || 0;
  const scrollTop = el.getBoundingClientRect().top + window.pageYOffset - headerHeight - 10;

  window.scrollTo({ top: scrollTop, behavior: 'smooth' });

  el.classList.add('mkp-highlight');
  setTimeout(() => el.classList.remove('mkp-highlight'), 2000);
});
</script>
</body>
</html>
