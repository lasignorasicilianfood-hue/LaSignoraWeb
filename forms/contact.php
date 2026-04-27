<?php
// CONFIGURAZIONE — cambia con la tua email
$receiving_email_address = "info@lasignorafood.com";

// Accetta solo POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

// Sanitizzazione input
$name    = strip_tags(trim($_POST["name"] ?? ""));
$email   = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
$subject = strip_tags(trim($_POST["subject"] ?? ""));
$message = strip_tags(trim($_POST["message"] ?? ""));

// Validazione
if (
    empty($name) ||
    empty($email) ||
    empty($subject) ||
    empty($message) ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    http_response_code(400);
    echo "Please fill all fields correctly.";
    exit;
}

// Corpo email
$email_content  = "Hai ricevuto un nuovo messaggio dal sito La Signora Sicilian Food:\n\n";
$email_content .= "Nome: $name\n";
$email_content .= "Email: $email\n";
$email_content .= "Oggetto: $subject\n\n";
$email_content .= "Messaggio:\n$message\n";

// Header email
$headers  = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";

// Invio email
if (mail($receiving_email_address, $subject, $email_content, $headers)) {
    http_response_code(200);
    echo "OK";
} else {
    http_response_code(500);
    echo "Email sending failed.";
}
?>
