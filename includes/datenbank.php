<?php
// includes/datenbank.php

$host = 'localhost';
$dbname = 'wollladen';    // <--- hier anpassen
$user = 'root';                    // oder dein DB-Benutzer
$pass = '';                        // bei XAMPP oft leer

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    // Fehler im Entwicklungsmodus anzeigen lassen
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Bei Fehler: Ausgabe und Abbruch
    die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
}
