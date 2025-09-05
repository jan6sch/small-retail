<?php session_start(); ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>
<body>
    <form action="login_check.php" method="post">
        <h2>Admin Login</h2>
        <label for="username">Benutzername:</label>
        <input type="text" name="username" required>
        <br><br>
        <label for="password">Passwort:</label>
        <input type="password" name="password" required>
        <br><br>
        <button type="submit">Anmelden</button>
    </form>
</body>
</html>
