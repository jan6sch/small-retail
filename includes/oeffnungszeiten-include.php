<?php
include __DIR__ . '/datenbank.php';

// Wochentag holen (1 = Montag, 7 = Sonntag)
$heuteTag = date('N'); 
$tageNamen = [1=>"Montag",2=>"Dienstag",3=>"Mittwoch",4=>"Donnerstag",5=>"Freitag",6=>"Samstag",7=>"Sonntag"];

$sql = "SELECT * FROM oeffnungszeiten WHERE tag = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$heuteTag]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$status = "<span class='status unknown'>Keine Daten</span>";
$zeitenText = "–";

if ($row) {
    $jetzt = date('H:i');
    $offen = $row['offen'];
    $zu = $row['zu'];
    $pauseAnfang = $row['pause_anfang'];
    $pauseEnde = $row['pause_ende'];

    // Sonntag oder alles 00:00:00
    if ($offen == "00:00:00" && $zu == "00:00:00") {
        $status = "<span class='status closed'>Geschlossen</span>";
        // nächsten Öffnungstag suchen
        $nextDay = $heuteTag;
        do {
            $nextDay = $nextDay % 7 + 1;
            $stmt2 = $pdo->prepare("SELECT * FROM oeffnungszeiten WHERE tag = ?");
            $stmt2->execute([$nextDay]);
            $nextRow = $stmt2->fetch(PDO::FETCH_ASSOC);
        } while ($nextRow && $nextRow['offen'] == "00:00:00" && $nextRow['zu'] == "00:00:00");

        if ($nextRow) {
            $status .= ", öffnet wieder " . $tageNamen[$nextDay] . " um " . date("H:i", strtotime($nextRow['offen'])) . " Uhr";
        }
        $zeitenText = "–";
    } else {
       // Normaler Tag
if ($jetzt >= $offen && $jetzt < $zu) {
    if ($pauseAnfang != "00:00:00" && $pauseEnde != "00:00:00" && $jetzt >= $pauseAnfang && $jetzt < $pauseEnde) {
        $status = "<span class='status closed'>Geschlossen (Pause bis " . date("H:i", strtotime($pauseEnde)) . ")</span>";
    } else {
        $status = "<span class='status open'>Geöffnet</span>";
    }
} else {
    // Geschlossen → nächste Öffnung berechnen
    $nextDay = $heuteTag;
    $nextRow = $row;

    // Wenn Laden heute noch öffnet
    if ($jetzt < $offen) {
        $status = "<span class='status closed'>Geschlossen</span>, öffnet wieder heute um " . date("H:i", strtotime($offen)) . " Uhr";
    } else {
        // Sonst nächsten Öffnungstag suchen
        do {
            $nextDay = $nextDay % 7 + 1;
            $stmt2 = $pdo->prepare("SELECT * FROM oeffnungszeiten WHERE tag = ?");
            $stmt2->execute([$nextDay]);
            $nextRow = $stmt2->fetch(PDO::FETCH_ASSOC);
        } while ($nextRow && $nextRow['offen'] == "00:00:00" && $nextRow['zu'] == "00:00:00");

        if ($nextRow) {
            $status = "<span class='status closed'>Geschlossen</span>, öffnet wieder " . 
                      $tageNamen[$nextDay] . " um " . date("H:i", strtotime($nextRow['offen'])) . " Uhr";
        }
    }
}


        // Zeiten für heute
        if ($pauseAnfang != "00:00:00" && $pauseEnde != "00:00:00") {
            $zeitenText = date("H:i", strtotime($offen)) . " – " . date("H:i", strtotime($pauseAnfang)) .
                          " | " . date("H:i", strtotime($pauseEnde)) . " – " . date("H:i", strtotime($zu));
        } else {
            $zeitenText = date("H:i", strtotime($offen)) . " – " . date("H:i", strtotime($zu));
        }
    }
}
?>

<div class="contact-box">
  <h3>Öffnungszeiten <i class="fa-solid fa-clock"></i></h3>
  <p class="day"><em>Heute ist <?php echo $tageNamen[$heuteTag]; ?></em></p>
  <p><?php echo $zeitenText; ?></p>
  <p><strong><?php echo $status; ?></strong></p>
  
</div>
