<?php
header('Content-Type: application/json; charset=utf-8');

// Passwort laden
$config = require __DIR__ . '/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/phpmailer/Exception.php';
require __DIR__ . '/phpmailer/PHPMailer.php';
require __DIR__ . '/phpmailer/SMTP.php';

// Nur POST erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Ungültige Methode']);
    exit;
}

// Sanitizing
function sanitize($str) {
    return trim(strip_tags(str_replace(["\r", "\n"], ' ', $str)));
}

// Honeypot (Anti-Spam)
if (!empty($_POST['website'] ?? '')) {
    echo json_encode(['success' => true]);
    exit;
}

// Felder einsammeln
$name    = sanitize($_POST['name'] ?? '');
$email   = sanitize($_POST['email'] ?? '');
$subject = sanitize($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$subject || !$message) {
    echo json_encode(['success' => false, 'message' => 'Bitte alle Felder ausfüllen.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Ungültige E-Mail-Adresse.']);
    exit;
}

/* ===========================================================
   HTML-Mail an dich
=========================================================== */

$adminHtml = "
<body style='margin:0; padding:0; font-family:Arial, sans-serif; background:#f4f4f4;'>

<!-- Header-Balken -->
<div style='background:#00bcd4; padding:18px; text-align:center;'>
    <h1 style='margin:0; font-size:22px; color:white; font-weight:600;'>
        Anfrage von {$name} für <strong>{$subject}</strong>
    </h1>
</div>

<!-- Hauptcontainer -->
<div style='max-width:600px; margin:25px auto; background:white; border-radius:10px;
            box-shadow:0 4px 12px rgba(0,0,0,0.1); padding:25px;'>

    <p style='font-size:16px; color:#333; margin:0 0 15px 0;'>
        Es ist eine neue Nachricht über das Kontaktformular auf 
        <strong>nils-digital.de</strong> eingegangen:
    </p>

    <table cellpadding='6' style='width:100%; font-size:15px; color:#444;'>
        <tr>
            <td style='width:140px; font-weight:bold;'>Name:</td>
            <td>{$name}</td>
        </tr>
        <tr>
            <td style='font-weight:bold;'>E-Mail:</td>
            <td>{$email}</td>
        </tr>
        <tr>
            <td style='font-weight:bold;'>Betreff:</td>
            <td>{$subject}</td>
        </tr>
    </table>

    <h3 style='margin-top:25px; font-size:18px; color:#00bcd4;'>Nachricht:</h3>

    <div style='background:#f9f9f9; padding:15px; border-left:4px solid #00bcd4;
                font-size:15px; color:#333; border-radius:6px;'>
        " . nl2br(htmlspecialchars($message)) . "
    </div>

</div>

<!-- Footer -->
    <img src='https://nils-digital.de/apple-touch-icon.png'
         alt='nils-digital'
         style='width:28px; height:28px; border-radius:6px; margin-bottom:6px;'>
<div style='text-align:center; padding:15px; color:#777; font-size:12px;'>
    <p style='margin:0;'>
        © " . date('Y') . " nils-digital.de<br>
        Automatisch generierte Benachrichtigung
    </p>
</div>

</body>
";


/* =========================================================== 
   Auto-Antwort an den Kunden
=========================================================== */
$replyHtml = "
<body style='margin:0; padding:0; font-family:Arial, sans-serif; background:#f4f4f4;'>

<!-- Header mit Bild -->
<div style='background:#00bcd4; padding:25px 15px; text-align:center;'>

    <img src='https://nils-digital.de/assets/images/sunny-nils.jpg'
         alt='Sunny & Nils'
         style='width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid white; margin-bottom:10px;'>

    <h1 style='margin:0; font-size:22px; color:white; font-weight:600;'>
        Danke {$name}, für deine Anfrage: {$subject}! 😊
    </h1>
</div>

<!-- Hauptcontainer -->
<div style='max-width:600px; margin:25px auto; background:white; border-radius:10px;
            box-shadow:0 4px 12px rgba(0,0,0,0.1); padding:25px;'>

    <p style='font-size:16px; color:#333; margin:0 0 15px 0;'>
        Hallo <strong>{$name}</strong>,<br><br>
        vielen Dank für deine Nachricht!
        Ich freue mich sehr über dein Interesse und melde mich in der Regel innerhalb von 24 Stunden bei dir.
    </p>

    <!-- NEU: Projektfragebogen -->
    <div style='margin-top:30px; padding:20px; background:#f2fbfd; border-radius:8px;'>

        <h3 style='margin-top:0; font-size:17px; color:#00bcd4;'>
            🔍Projekt konkretisieren
        </h3>

        <p style='font-size:15px; color:#444;'>
            Beantworte mir doch bitte folgende Fragen.
            Das hilft mir bei der Vorbereitung und deiner persönlichen Angebotserstellung.
        </p>

        <div style='text-align:center; margin:20px 0;'>
            <a href='https://nils-digital.de/pages/projektfragebogen.html'
               target='_blank'
               style='display:inline-block; padding:14px 26px;
                      background:#00bcd4; color:white; text-decoration:none;
                      font-size:16px; border-radius:30px; font-weight:600;'>
                ➜ Zum Projekt-Fragebogen
            </a>
        </div>

        <p style='font-size:13px; color:#666; margin:0;'>
            ⏱️ Dauer: ca. 3–5 Minuten
        </p>
    </div>

    <h3 style='font-size:18px; color:#00bcd4; margin-top:25px;'>Deine Nachricht:</h3>

    <div style='background:#f9f9f9; padding:15px; border-left:4px solid #00bcd4;
                font-size:15px; color:#333; border-radius:6px;'>
        <strong>Betreff:</strong> {$subject}<br><br>
        " . nl2br(htmlspecialchars($message)) . "
    </div>

    <p style='margin-top:25px; font-size:15px; color:#555;'>
        Wenn du weitere Fragen hast, kannst du auf diese E-Mail jederzeit direkt antworten.  
        Deine Antwort landet sofort bei mir.
    </p>

    <!-- Social Icons -->
    <div style='text-align:center; margin-top:30px;'>
        <a href='https://www.tiktok.com/@sunnycam28' target='_blank' style='text-decoration:none;'>
            <img src='https://nils-digital.de/assets/images/icons/tiktok_logo_icon.png'
                 alt='TikTok'
                 style='width:42px; height:42px; margin:0 5px;'>
        </a>

        <p style='font-size:13px; color:#777; margin-top:8px;'>
            Folge SunnyCam auf TikTok 🐾
        </p>
    </div>

</div>

<!-- Footer -->
<div style='text-align:center; padding:15px; color:#777; font-size:12px;'>
    <img src='https://nils-digital.de/logo/logo.png'
         alt='nils-digital'
         style='width:28px; height:28px; border-radius:6px; margin-bottom:6px;'><br>

    © " . date('Y') . " nils-digital.de – automatische Bestätigung
</div>

</body>
";



/* ===========================================================
   SMTP Einstellungen (STRATO)
=========================================================== */

function sendMailSMTP($to, $subject, $html, $replyTo = null, $config = null) {
    $mail = new PHPMailer(true);

    try {
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->isSMTP();
        $mail->Host       = 'smtp.strato.de';
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['smtp_user'];
        $mail->Password   = $config['smtp_pass'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom($config['smtp_user'], 'Nils-Digital');
        $mail->addAddress($to);

        if ($replyTo) {
            $mail->addReplyTo($replyTo);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}

// Mail an dich
$sentAdmin = sendMailSMTP(
    "info@nils-digital.de",
    "Neue Nachricht von {$name} – {$subject}",
    $adminHtml,
    $email,
    $config
);

// Auto-Antwort
if ($sentAdmin) {
    sendMailSMTP(
        $email,
        "Danke für deine Nachricht!",
        $replyHtml,
        "info@nils-digital.de",
        $config
    );
}

// Ergebnis an Frontend
echo json_encode(['success' => $sentAdmin]);
