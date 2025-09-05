<?php
// Dein Passwort hier eintragen:
$plainPassword = "MeinSuperGeheimesPW2025!";

// Hash erzeugen und ausgeben
echo password_hash($plainPassword, PASSWORD_DEFAULT);
