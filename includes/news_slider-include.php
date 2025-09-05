<?php include __DIR__ . '/datenbank.php'; 
$sql = "SELECT bild_pfad, alt FROM bilder WHERE wichtigkeit = 1"; $stmt = $pdo->query($sql); $news = $stmt->fetchAll(PDO::FETCH_ASSOC); ?> 
<!-- NEWS Swiper-Slider --> 
 <div class="swiper news-slider"> 
  <div class="swiper-wrapper"> 
    <?php foreach ($news as $eintrag): ?> 
      <div class="swiper-slide"> 
        <img src="../db_bilder/<?php echo htmlspecialchars($eintrag['bild_pfad']); ?>" alt="<?php echo htmlspecialchars($eintrag['alt']); ?>" /> </div> 
        <?php endforeach; ?> </div> 
      </div>