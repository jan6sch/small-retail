<?php
session_start();
require_once __DIR__ . "/../private/config.php"; // Config laden

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if ($username === $ALLOWED_USERNAME && password_verify($password, $ADMIN_PASS_HASH)) {
        $_SESSION["admin_logged_in"] = true;
        header("Location: adminmenu.php");
        exit;
    } else {
        echo "❌ Falsche Zugangsdaten.";
    }
}
