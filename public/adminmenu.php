<?php
session_start();
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Menü</title>
</head>
<body>
   
    <a href="logout.php">Logout</a>


<?php
include '../includes/datenbank.php';

// --- Update-Handler nur für Socials ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bereich']) && $_POST['bereich'] === 'socials') {
    if (isset($_POST['socials'])) {
        foreach ($_POST['socials'] as $id => $data) {
            $url = $data['url'] ?? '';
            $wichtigkeit = isset($data['wichtigkeit']) ? 1 : 0;
            $reihenfolge = (int)($data['reihenfolge'] ?? 0);

            $stmt = $pdo->prepare("
                UPDATE socials 
                SET url = :url, wichtigkeit = :wichtigkeit, reihenfolge = :reihenfolge 
                WHERE id = :id
            ");
            $stmt->execute([
                ':url' => $url,
                ':wichtigkeit' => $wichtigkeit,
                ':reihenfolge' => $reihenfolge,
                ':id' => $id
            ]);
        }
    }
}

// --- Daten laden: wichtigkeit=1 zuerst ---
$stmt = $pdo->query("
    SELECT id, plattform, url, wichtigkeit, reihenfolge 
    FROM socials 
    ORDER BY wichtigkeit DESC, reihenfolge ASC
");
$socials = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Vorschau bauen ---
$preview = '<div class="socials">';
foreach ($socials as $s) {
    if ($s['wichtigkeit'] == 1) {
        $plattform = strtolower($s['plattform']);
        $url = htmlspecialchars($s['url']);

        switch ($plattform) {
            case 'instagram': $icon = '<i class="fa-brands fa-instagram fa-xl"></i>'; break;
            case 'facebook':  $icon = '<i class="fa-brands fa-square-facebook fa-xl"></i>'; break;
            case 'twitter':   $icon = '<i class="fa-brands fa-square-x-twitter fa-xl"></i>'; break;
            case 'youtube':   $icon = '<i class="fa-brands fa-youtube fa-xl"></i>'; break;
            case 'tiktok':    $icon = '<i class="fa-brands fa-tiktok fa-lg"></i>'; break;
            case 'pinterest': $icon = '<i class="fa-brands fa-square-pinterest fa-xl"></i>'; break;
            default:          $icon = '<i class="fa-solid fa-hashtag fa-lg"></i>'; break;
        }

        $preview .= '<div class="social-icon"><a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $icon . '</a></div>';
    }
}
$preview .= '</div>';
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin – Socials</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        .socials { display: flex; gap: 10px; margin-bottom: 20px; }
        .social-icon a { color: #333; font-size: 24px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #f4f4f4; }
        input[type="text"], input[type="number"] { width: 95%; padding: 4px; }
        .save-btn { margin-top: 10px; padding: 8px 16px; background: #0073e6; color: white; border: none; cursor: pointer; }
        .save-btn:hover { background: #005bb5; }
        .hint { background: #ffefc2; padding: 8px; margin-bottom: 15px; border: 1px solid #e0b100; }
    </style>
</head>
<body>

<h2>Vorschau</h2>
<?php echo $preview; ?>

<h2>Socials bearbeiten</h2>
<div class="hint">
    Hinweis: Es sollten nicht mehr als <strong>4 Socials</strong> aktiv (Wichtigkeit = 1) sein.
</div>

<form method="post">
    <input type="hidden" name="bereich" value="socials">
    <table>
        <tr>
            <th>Plattform</th>
            <th>URL</th>
            <th>Wichtigkeit</th>
            <th>Reihenfolge</th>
        </tr>
        <?php foreach ($socials as $s): ?>
            <tr>
                <td><?php echo htmlspecialchars($s['plattform']); ?></td>
                <td>
                    <input type="text" name="socials[<?php echo $s['id']; ?>][url]" value="<?php echo htmlspecialchars($s['url']); ?>">
                </td>
                <td>
                    <input type="checkbox" name="socials[<?php echo $s['id']; ?>][wichtigkeit]" value="1" <?php if ($s['wichtigkeit']) echo 'checked'; ?>>
                </td>
                <td>
                    <input type="number" name="socials[<?php echo $s['id']; ?>][reihenfolge]" value="<?php echo (int)$s['reihenfolge']; ?>" min="0">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <button type="submit" class="save-btn">Alle Änderungen speichern</button>
</form>













<!--#######################################################################################################################-->



<?php


// --- News-Handler ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bereich']) && $_POST['bereich'] === 'news') {
    // Bestehende News updaten
    if (isset($_POST['news'])) {
        foreach ($_POST['news'] as $id => $data) {
            $titel = $data['titel'] ?? '';
            $text = $data['text'] ?? '';
            $anfangsdatum = $data['anfangsdatum'] ?? null;
            $enddatum = $data['enddatum'] ?? null;
            $wichtigkeit = isset($data['wichtigkeit']) ? 1 : 0;
            $aktiv = isset($data['aktiv']) ? 1 : 0;

            $stmt = $pdo->prepare("
                UPDATE news
                SET titel = :titel, text = :text, anfangsdatum = :anfangsdatum, enddatum = :enddatum, wichtigkeit = :wichtigkeit, aktiv = :aktiv
                WHERE id = :id
            ");
            $stmt->execute([
                ':titel' => $titel,
                ':text' => $text,
                ':anfangsdatum' => $anfangsdatum,
                ':enddatum' => $enddatum,
                ':wichtigkeit' => $wichtigkeit,
                ':aktiv' => $aktiv,
                ':id' => $id
            ]);
        }
    }

    // Neue News anlegen
    if (!empty($_POST['new']['titel']) && !empty($_POST['new']['text'])) {
        $stmt = $pdo->prepare("
            INSERT INTO news (titel, text, anfangsdatum, enddatum, wichtigkeit, aktiv)
            VALUES (:titel, :text, :anfangsdatum, :enddatum, :wichtigkeit, :aktiv)
        ");
        $stmt->execute([
            ':titel' => $_POST['new']['titel'],
            ':text' => $_POST['new']['text'],
            ':anfangsdatum' => $_POST['new']['anfangsdatum'] ?: null,
            ':enddatum' => $_POST['new']['enddatum'] ?: null,
            ':wichtigkeit' => isset($_POST['new']['wichtigkeit']) ? 1 : 0,
            ':aktiv' => isset($_POST['new']['aktiv']) ? 1 : 0
        ]);
    }

    // News löschen
    if (!empty($_POST['delete'])) {
        foreach ($_POST['delete'] as $id) {
            $stmt = $pdo->prepare("DELETE FROM news WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }
    }
}

// --- Daten laden ---
$stmt = $pdo->query("SELECT id, titel, text, anfangsdatum, enddatum, wichtigkeit, aktiv FROM news ORDER BY wichtigkeit DESC, anfangsdatum DESC");
$news = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin – News</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        th { background: #f4f4f4; }
        input[type="text"], textarea, input[type="date"] { width: 95%; padding: 4px; }
        textarea { height: 80px; }
        .save-btn { margin-top: 10px; padding: 8px 16px; background: #0073e6; color: white; border: none; cursor: pointer; }
        .save-btn:hover { background: #005bb5; }
        .delete-box { text-align: center; }
    </style>
</head>
<body>

<h2>News verwalten</h2>
<div class="hint">
    Hinweis: Auf der Startseite werden maximal <strong>5 aktive News</strong> angezeigt.
</div>

<form method="post">
    <input type="hidden" name="bereich" value="news">
    <table>
        <tr>
            <th>Titel</th>
            <th>Text</th>
            <th>Startdatum</th>
            <th>Enddatum</th>
            <th>Wichtigkeit</th>
            <th>Aktiv</th>
            <th>Löschen</th>
        </tr>
        <?php foreach ($news as $n): ?>
            <tr>
                <td>
                    <input type="text" name="news[<?php echo $n['id']; ?>][titel]" value="<?php echo htmlspecialchars($n['titel']); ?>">
                </td>
                <td>
                    <textarea name="news[<?php echo $n['id']; ?>][text]"><?php echo htmlspecialchars($n['text']); ?></textarea>
                </td>
                <td>
                    <input type="date" name="news[<?php echo $n['id']; ?>][anfangsdatum]" value="<?php echo htmlspecialchars($n['anfangsdatum']); ?>">
                </td>
                <td>
                    <input type="date" name="news[<?php echo $n['id']; ?>][enddatum]" value="<?php echo htmlspecialchars($n['enddatum']); ?>">
                </td>
                <td style="text-align:center;">
                    <input type="checkbox" name="news[<?php echo $n['id']; ?>][wichtigkeit]" value="1" <?php if ($n['wichtigkeit']) echo 'checked'; ?>>
                </td>
                <td style="text-align:center;">
                    <input type="checkbox" name="news[<?php echo $n['id']; ?>][aktiv]" value="1" <?php if ($n['aktiv']) echo 'checked'; ?>>
                </td>
                <td class="delete-box">
                    <input type="checkbox" name="delete[]" value="<?php echo $n['id']; ?>">
                </td>
            </tr>
        <?php endforeach; ?>
        <!-- Neue News -->
        <tr>
            <td><input type="text" name="new[titel]" placeholder="Neuer Titel"></td>
            <td><textarea name="new[text]" placeholder="Neuer Text"></textarea></td>
            <td><input type="date" name="new[anfangsdatum]"></td>
            <td><input type="date" name="new[enddatum]"></td>
            <td style="text-align:center;"><input type="checkbox" name="new[wichtigkeit]" value="1"></td>
            <td style="text-align:center;"><input type="checkbox" name="new[aktiv]" value="1"></td>
            <td></td>
        </tr>
    </table>
    <button type="submit" class="save-btn">Alle Änderungen speichern</button>
</form>
























<?php
$uploadDir = __DIR__ . '/../db_bilder/'; // Admin liegt in /public, also eins hoch
$uploadPathDB = 'db_bilder/'; // so wird es in der DB gespeichert

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bereich']) && $_POST['bereich'] === 'bilder') {
    // Vorhandene Bilder updaten
    if (isset($_POST['bilder'])) {
        foreach ($_POST['bilder'] as $id => $data) {
            $alt = $data['alt'] ?? '';
            $wichtigkeit = isset($data['wichtigkeit']) ? 1 : 0;

            $stmt = $pdo->prepare("UPDATE bilder SET alt = :alt, wichtigkeit = :wichtigkeit WHERE id = :id");
            $stmt->execute([
                ':alt' => $alt,
                ':wichtigkeit' => $wichtigkeit,
                ':id' => $id
            ]);
        }
    }

    // Neues Bild hochladen
    if (!empty($_FILES['new_bild']['name'])) {
        $fileName = time() . '_' . basename($_FILES['new_bild']['name']);
        $targetPath = $uploadDir . $fileName;
        $dbPath = $fileName; // nur der Dateiname, ohne Ordner

        if (move_uploaded_file($_FILES['new_bild']['tmp_name'], $targetPath)) {
            $alt = $_POST['new']['alt'] ?? '';
            $wichtigkeit = isset($_POST['new']['wichtigkeit']) ? 1 : 0;

            $stmt = $pdo->prepare("
                INSERT INTO bilder (bild_pfad, alt, wichtigkeit)
                VALUES (:bild_pfad, :alt, :wichtigkeit)
            ");
            $stmt->execute([
                ':bild_pfad' => $dbPath,
                ':alt' => $alt,
                ':wichtigkeit' => $wichtigkeit
            ]);
        }
    }

    // Bilder löschen
    if (!empty($_POST['delete'])) {
        foreach ($_POST['delete'] as $id) {
            // Bildpfad holen, Datei löschen
            $stmt = $pdo->prepare("SELECT bild_pfad FROM bilder WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $file = $stmt->fetchColumn();
            if ($file && file_exists(__DIR__ . '/../db_bilder/' . $file)) {
                unlink(__DIR__ . '/../db_bilder/' . $file);
            }
            // DB-Eintrag löschen
            $stmt = $pdo->prepare("DELETE FROM bilder WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }
    }
}

// Bilder laden
$stmt = $pdo->query("SELECT id, bild_pfad, wichtigkeit, alt FROM bilder ORDER BY wichtigkeit DESC, id DESC");
$bilder = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin – Bilder</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #f4f4f4; }
        img { 
    width: 150px;      /* feste Breite */
    height: 150px;     /* feste Höhe = quadratisch */
    object-fit: cover; /* Bild wird beschnitten, aber nicht verzerrt */
    border-radius: 4px; /* optional: leicht abgerundet */
}

        input[type="text"] { width: 95%; padding: 4px; }
        .save-btn { margin-top: 10px; padding: 8px 16px; background: #0073e6; color: white; border: none; cursor: pointer; }
        .save-btn:hover { background: #005bb5; }
    </style>
</head>
<body>

<h2>Bilder verwalten</h2>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="bereich" value="bilder">
    <table>
        <tr>
            <th>Vorschau</th>
            <th>Pfad</th>
            <th>Alt-Text</th>
            <th>Wichtigkeit</th>
            <th>Löschen</th>
        </tr>
        <?php foreach ($bilder as $b): ?>
            <tr>
                <td>
                    <?php if ($b['bild_pfad'] && file_exists(__DIR__ . '/../db_bilder/' . $b['bild_pfad'])): ?>
                        <img src="../db_bilder/<?php echo htmlspecialchars($b['bild_pfad']); ?>" 
                            <img src="../db_bilder/<?php echo htmlspecialchars($b['bild_pfad']); ?>" alt="<?php echo htmlspecialchars($b['alt']); ?>" />

                    <?php else: ?>
                        (kein Bild)
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($b['bild_pfad']); ?></td>
                <td>
                    <input type="text" name="bilder[<?php echo $b['id']; ?>][alt]" 
                           value="<?php echo htmlspecialchars($b['alt']); ?>">
                </td>
                <td>
                    <input type="checkbox" name="bilder[<?php echo $b['id']; ?>][wichtigkeit]" value="1" 
                           <?php if ($b['wichtigkeit']) echo 'checked'; ?>>
                </td>
                <td>
                    <input type="checkbox" name="delete[]" value="<?php echo $b['id']; ?>">
                </td>
            </tr>
        <?php endforeach; ?>
        <!-- Neues Bild -->
        <tr>
            <td colspan="2">
                <input type="file" name="new_bild">
            </td>
            <td>
                <input type="text" name="new[alt]" placeholder="Alt-Text">
            </td>
            <td>
                <input type="checkbox" name="new[wichtigkeit]" value="1">
            </td>
            <td></td>
        </tr>
    </table>
    <button type="submit" class="save-btn">Alle Änderungen speichern</button>
</form>















<?php
$uploadDir = __DIR__ . '/../db_bilder/'; // Logos liegen ebenfalls in db_bilder/
$uploadPathDB = 'db_bilder/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bereich']) && $_POST['bereich'] === 'marken') {
    // Vorhandene Marken updaten
    if (isset($_POST['marken'])) {
        foreach ($_POST['marken'] as $id => $data) {
            $name        = $data['name'] ?? '';
            $beschreibung = $data['beschreibung'] ?? '';
            $wichtigkeit = isset($data['wichtigkeit']) ? 1 : 0;
            $reihenfolge = (int)($data['reihenfolge'] ?? 0);

            $stmt = $pdo->prepare("
                UPDATE marken 
                SET name = :name, beschreibung = :beschreibung, wichtigkeit = :wichtigkeit, reihenfolge = :reihenfolge
                WHERE id = :id
            ");
            $stmt->execute([
                ':name' => $name,
                ':beschreibung' => $beschreibung,
                ':wichtigkeit' => $wichtigkeit,
                ':reihenfolge' => $reihenfolge,
                ':id' => $id
            ]);
        }
    }

    // Neues Logo hochladen
    if (!empty($_FILES['new_logo']['name'])) {
        $fileName   = time() . '_' . basename($_FILES['new_logo']['name']);
        $targetPath = $uploadDir . $fileName;
        $dbPath     = $fileName; // nur Dateiname speichern

        if (move_uploaded_file($_FILES['new_logo']['tmp_name'], $targetPath)) {
            $name        = $_POST['new']['name'] ?? '';
            $beschreibung = $_POST['new']['beschreibung'] ?? '';
            $wichtigkeit = isset($_POST['new']['wichtigkeit']) ? 1 : 0;
            $reihenfolge = (int)($_POST['new']['reihenfolge'] ?? 0);

            $stmt = $pdo->prepare("
                INSERT INTO marken (name, beschreibung, logo_pfad, wichtigkeit, reihenfolge)
                VALUES (:name, :beschreibung, :logo_pfad, :wichtigkeit, :reihenfolge)
            ");
            $stmt->execute([
                ':name' => $name,
                ':beschreibung' => $beschreibung,
                ':logo_pfad' => $dbPath,
                ':wichtigkeit' => $wichtigkeit,
                ':reihenfolge' => $reihenfolge
            ]);
        }
    }

    // Marken löschen
    if (!empty($_POST['delete'])) {
        foreach ($_POST['delete'] as $id) {
            $stmt = $pdo->prepare("SELECT logo_pfad FROM marken WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $file = $stmt->fetchColumn();
            if ($file && file_exists(__DIR__ . '/../db_bilder/' . $file)) {
                unlink(__DIR__ . '/../db_bilder/' . $file);
            }
            $stmt = $pdo->prepare("DELETE FROM marken WHERE id = :id");
            $stmt->execute([':id' => $id]);
        }
    }
}

// Marken laden
$stmt = $pdo->query("SELECT id, name, beschreibung, logo_pfad, wichtigkeit, reihenfolge FROM marken ORDER BY reihenfolge ASC, wichtigkeit DESC, id DESC");
$marken = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin – Marken</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; vertical-align: middle; }
        th { background: #f4f4f4; }
        img { 
            width: 150px; 
            height: 150px; 
            object-fit: cover; 
            border-radius: 4px;
        }
        input[type="text"], textarea { width: 95%; padding: 4px; }
        textarea { height: 60px; }
        .save-btn { margin-top: 10px; padding: 8px 16px; background: #0073e6; color: white; border: none; cursor: pointer; }
        .save-btn:hover { background: #005bb5; }
    </style>
</head>
<body>

<h2>Marken verwalten</h2>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="bereich" value="marken">
    <table>
        <tr>
            <th>Logo</th>
            <th>Name</th>
            <th>Beschreibung</th>
            <th>Wichtigkeit</th>
            <th>Reihenfolge</th>
            <th>Löschen</th>
        </tr>
        <?php foreach ($marken as $m): ?>
            <tr>
                <td>
                    <?php if ($m['logo_pfad'] && file_exists(__DIR__ . '/../db_bilder/' . $m['logo_pfad'])): ?>
                        <img src="../db_bilder/<?php echo htmlspecialchars($m['logo_pfad']); ?>" 
                             alt="<?php echo htmlspecialchars($m['name']); ?>" />
                    <?php else: ?>
                        (kein Logo)
                    <?php endif; ?>
                </td>
                <td>
                    <input type="text" name="marken[<?php echo $m['id']; ?>][name]" 
                           value="<?php echo htmlspecialchars($m['name']); ?>">
                </td>
                <td>
                    <textarea name="marken[<?php echo $m['id']; ?>][beschreibung]"><?php echo htmlspecialchars($m['beschreibung']); ?></textarea>
                </td>
                <td>
                    <input type="checkbox" name="marken[<?php echo $m['id']; ?>][wichtigkeit]" value="1" 
                           <?php if ($m['wichtigkeit']) echo 'checked'; ?>>
                </td>
                <td>
                    <input type="text" name="marken[<?php echo $m['id']; ?>][reihenfolge]" 
                           value="<?php echo htmlspecialchars($m['reihenfolge']); ?>" size="3">
                </td>
                <td>
                    <input type="checkbox" name="delete[]" value="<?php echo $m['id']; ?>">
                </td>
            </tr>
        <?php endforeach; ?>
        <!-- Neue Marke -->
        <tr>
            <td><input type="file" name="new_logo"></td>
            <td><input type="text" name="new[name]" placeholder="Name"></td>
            <td><textarea name="new[beschreibung]" placeholder="Beschreibung"></textarea></td>
            <td><input type="checkbox" name="new[wichtigkeit]" value="1"></td>
            <td><input type="text" name="new[reihenfolge]" placeholder="0" size="3"></td>
            <td></td>
        </tr>
    </table>
    <button type="submit" class="save-btn">Alle Änderungen speichern</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bereich']) && $_POST['bereich'] === 'oeffnungszeiten') {
    if (isset($_POST['zeiten'])) {
        foreach ($_POST['zeiten'] as $id => $data) {
            $offen        = $data['offen'] ?: null;
            $zu           = $data['zu'] ?: null;
            $pause_anfang = $data['pause_anfang'] ?: null;
            $pause_ende   = $data['pause_ende'] ?: null;

            $stmt = $pdo->prepare("
                UPDATE oeffnungszeiten 
                SET offen = :offen, zu = :zu, pause_anfang = :pause_anfang, pause_ende = :pause_ende
                WHERE id = :id
            ");
            $stmt->execute([
                ':offen' => $offen,
                ':zu' => $zu,
                ':pause_anfang' => $pause_anfang,
                ':pause_ende' => $pause_ende,
                ':id' => $id
            ]);
        }
    }
}

// Öffnungszeiten laden
$stmt = $pdo->query("SELECT id, tag, offen, zu, pause_anfang, pause_ende FROM oeffnungszeiten ORDER BY id ASC");
$zeiten = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mapping: 1–7 → Montag–Sonntag
$tageMap = [
    1 => 'Montag',
    2 => 'Dienstag',
    3 => 'Mittwoch',
    4 => 'Donnerstag',
    5 => 'Freitag',
    6 => 'Samstag',
    7 => 'Sonntag'
];
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin – Öffnungszeiten</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; vertical-align: middle; }
        th { background: #f4f4f4; }
        input[type="time"] { width: 90%; padding: 4px; }
        .save-btn { margin-top: 10px; padding: 8px 16px; background: #0073e6; color: white; border: none; cursor: pointer; }
        .save-btn:hover { background: #005bb5; }
    </style>
</head>
<body>

<h2>Öffnungszeiten verwalten</h2>

<form method="post">
    <input type="hidden" name="bereich" value="oeffnungszeiten">
    <table>
        <tr>
            <th>Tag</th>
            <th>Öffnet</th>
            <th>Schließt</th>
            <th>Pause von</th>
            <th>Pause bis</th>
        </tr>
        <?php foreach ($zeiten as $z): ?>
            <tr>
                <td>
                    <?php echo $tageMap[$z['tag']] ?? $z['tag']; ?>
                </td>
                <td>
                    <input type="time" name="zeiten[<?php echo $z['id']; ?>][offen]" 
                           value="<?php echo htmlspecialchars($z['offen']); ?>">
                </td>
                <td>
                    <input type="time" name="zeiten[<?php echo $z['id']; ?>][zu]" 
                           value="<?php echo htmlspecialchars($z['zu']); ?>">
                </td>
                <td>
                    <input type="time" name="zeiten[<?php echo $z['id']; ?>][pause_anfang]" 
                           value="<?php echo htmlspecialchars($z['pause_anfang']); ?>">
                </td>
                <td>
                    <input type="time" name="zeiten[<?php echo $z['id']; ?>][pause_ende]" 
                           value="<?php echo htmlspecialchars($z['pause_ende']); ?>">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <button type="submit" class="save-btn">Alle Änderungen speichern</button>
</form>




</body>
</html>





