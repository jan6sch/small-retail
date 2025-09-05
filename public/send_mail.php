<?php
header('Location: ../public');
exit;
?>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST['name']);
    $userEmail = htmlspecialchars($_POST['email']);
    $title = htmlspecialchars($_POST['title']);
    $message = htmlspecialchars($_POST['message']);

    $to = "deine-email@example.com"; 
    $subject = "Neue Kontaktanfrage: " . $title;

    $body = "Name: $name\n";
    $body .= "E-Mail: $userEmail\n\n";
    $body .= "Nachricht:\n$message\n";

    $headers = "From: kontaktformular@deinedomain.de\r\n";
    $headers .= "Reply-To: $userEmail\r\n";

    if (mail($to, $subject, $body, $headers)) {
        echo "Danke, deine Nachricht wurde erfolgreich versendet!";
    } else {
        echo "Fehler beim Versenden der Nachricht. Bitte später erneut versuchen.";
    }
}
?>
