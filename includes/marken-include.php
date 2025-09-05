<?php
include __DIR__ . '/datenbank.php';

$sql = "SELECT id, name, beschreibung, logo_pfad, reihenfolge FROM marken WHERE wichtigkeit = 1 ORDER BY reihenfolge ASC";
$stmt = $pdo->query($sql);
$marken = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container swiper">
  <div class="card-wrapper">
    <ul class="card-list swiper-wrapper">
      <?php foreach ($marken as $marke): ?>
        <li class="card-item swiper-slide">
          <!-- Stellen Sie sicher, dass der Link korrekt zur marken.php führt -->
          <a href="../public/marken.php?marke=<?php echo (int)$marke['id']; ?>" class="card-link">
            <img src="../db_bilder/<?php echo htmlspecialchars($marke['logo_pfad']); ?>"
                 alt="Logo von <?php echo htmlspecialchars($marke['name']); ?>"
                 class="card-image">
            <p class="badge"><?php echo htmlspecialchars($marke['name']); ?></p>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>

    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
</div>
