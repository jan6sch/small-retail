<?php
include __DIR__ . '/../includes/datenbank.php';

$sql = "SELECT id, titel, text, anfangsdatum 
        FROM news 
        WHERE aktiv = 1 
        ORDER BY anfangsdatum DESC";
$stmt = $pdo->query($sql);
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <title>Alle News</title>
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

<main class="nwp-container">
  <h1 class="nwp-heading">Alle News</h1>
  <section class="nwp-list" aria-label="News-Liste">
    <?php if (empty($news)): ?>
      <p class="nwp-empty">Zurzeit gibt es keine News.</p>
    <?php else: ?>
      <?php foreach ($news as $n): ?>
        <article id="news-<?= (int)$n['id'] ?>" class="nwp-item" tabindex="-1">
          <div class="nwp-content">
            <h2 class="nwp-title"><?= htmlspecialchars($n['titel']) ?></h2>
            <div class="nwp-date"><?= date("d.m.Y", strtotime($n['anfangsdatum'])) ?></div>
            <div class="nwp-text"><?= nl2br(htmlspecialchars($n['text'])) ?></div>
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

<script>
window.addEventListener('load', () => {
  const params = new URLSearchParams(window.location.search);
  const id = params.get('news');
  if (!id) return;

  const el = document.getElementById('news-' + id);
  if (!el) return;

  const headerHeight = document.querySelector('header')?.offsetHeight || 0;
  const scrollTop = el.getBoundingClientRect().top + window.pageYOffset - headerHeight - 10;

  window.scrollTo({ top: scrollTop, behavior: 'smooth' });

  el.classList.add('nwp-highlight');
  setTimeout(() => el.classList.remove('nwp-highlight'), 2000);
});
</script>
</body>
</html>
