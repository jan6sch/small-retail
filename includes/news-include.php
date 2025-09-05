<?php
include __DIR__ . '/datenbank.php';

$heute = date("Y-m-d");

$query = "
    SELECT id, titel, text, anfangsdatum, enddatum, wichtigkeit 
    FROM news 
    WHERE :heute BETWEEN anfangsdatum AND enddatum 
      AND wichtigkeit = 1 AND aktiv = 1
    ORDER BY anfangsdatum DESC 
    LIMIT 3
";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':heute', $heute, PDO::PARAM_STR);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $anzeigeDatum = date("d.m.Y", strtotime($row['anfangsdatum']));
        $tageAlt = (new DateTime($row['anfangsdatum']))->diff(new DateTime($heute))->days;
        $tageText = ($tageAlt === 0) ? "Heute" : "Vor $tageAlt Tag" . ($tageAlt > 1 ? "en" : "");

        // Text kürzen
        $kurztext = mb_strimwidth($row['text'], 0, 200, "...");

        echo '
        <div class="news-entry">
            <div class="news-header-inner">
                <h3><a href="../public/news.php?news=' . (int)$row['id'] . '">' 
                     . htmlspecialchars($row['titel']) . '</a></h3>
                <div class="news-date-small">' . $tageText . '</div>
            </div>
            <div class="news-content">
                <p>' . nl2br(htmlspecialchars($kurztext)) . ' 
                   <a href="../public/news.php?news=' . (int)$row['id'] . '">mehr lesen</a></p>
            </div>
        </div>
        ';
    }
} else {
    echo '<p>Heute nix Neues.</p>';
}
?>
