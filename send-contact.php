<?php
/**
 * Envío formulario de contacto — Productos Don José
 * POST (respuesta JSON). PHPMailer + SMTP.
 */

declare(strict_types=1);

define('DONJOSE_CONTACT', true);
require __DIR__ . '/send-contact-config.php';

$phpMailerLoaded = false;

$autoloadCandidates = [
    __DIR__ . '/vendor/autoload.php',
];

foreach ($autoloadCandidates as $autoload) {
    if (is_file($autoload)) {
        require $autoload;
        $phpMailerLoaded = true;
        break;
    }
}

if (!$phpMailerLoaded) {
    $phpMailerLibBase = __DIR__ . '/lib/phpmailer/src/';
    $phpMailerLibFiles = [
        $phpMailerLibBase . 'Exception.php',
        $phpMailerLibBase . 'PHPMailer.php',
        $phpMailerLibBase . 'SMTP.php',
    ];

    $allLibFilesExist = true;
    foreach ($phpMailerLibFiles as $file) {
        if (!is_file($file)) {
            $allLibFilesExist = false;
            break;
        }
    }

    if ($allLibFilesExist) {
        require_once $phpMailerLibFiles[0];
        require_once $phpMailerLibFiles[1];
        require_once $phpMailerLibFiles[2];
        $phpMailerLoaded = true;
    }
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

const ASUNTO_LABELS = [
    'distribucion' => 'Consulta de distribución',
    'producto'     => 'Información sobre productos',
    'comercial'    => 'Consulta comercial',
    'prensa'       => 'Prensa y medios',
    'otro'         => 'Otro',
];

function sendJson(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJson(['success' => false, 'message' => 'Método no permitido.'], 405);
}

if (!$phpMailerLoaded) {
    error_log('[DonJose Form] PHPMailer no encontrado. Revisa /vendor/autoload.php o /lib/phpmailer/src/.');
    sendJson(['success' => false, 'message' => 'Error técnico temporal. Inténtalo más tarde.'], 500);
}

if (trim((string) ($_POST['nombre'] ?? '')) === '' && trim((string) ($_POST['email'] ?? '')) === '' && trim((string) ($_POST['mensaje'] ?? '')) === '') {
    sendJson(['success' => false, 'message' => 'Datos insuficientes.'], 400);
}

$honeypot = trim((string) ($_POST['web_site'] ?? ''));
if ($honeypot !== '') {
    sendJson(['success' => true, 'message' => 'Mensaje recibido.'], 200);
}

$nombre    = trim((string) ($_POST['nombre'] ?? ''));
$apellidos = trim((string) ($_POST['apellidos'] ?? ''));
$email     = trim((string) ($_POST['email'] ?? ''));
$telefono  = trim((string) ($_POST['telefono'] ?? ''));
$asunto    = trim((string) ($_POST['asunto'] ?? ''));
$mensaje   = trim((string) ($_POST['mensaje'] ?? ''));
$privacidad = isset($_POST['privacidad']) && $_POST['privacidad'] !== '';

$nombre    = mb_substr(preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $nombre), 0, 100);
$apellidos = mb_substr(preg_replace('/[^\p{L}\p{N}\s\-]/u', '', $apellidos), 0, 150);
$email     = filter_var($email, FILTER_SANITIZE_EMAIL);
$telefono  = mb_substr(preg_replace('/[^0-9+\s\-()]/', '', $telefono), 0, 30);
$asunto    = isset(ASUNTO_LABELS[$asunto]) ? $asunto : '';
$mensaje   = strip_tags($mensaje);
$mensaje   = mb_substr($mensaje, 0, 5000);
$mensaje   = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');

$errors = [];

if ($nombre === '') {
    $errors[] = 'El nombre es obligatorio.';
}
if ($email === '') {
    $errors[] = 'El email es obligatorio.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'El email no es válido.';
}
if ($asunto === '') {
    $errors[] = 'Debes seleccionar un asunto.';
}
if ($mensaje === '') {
    $errors[] = 'El mensaje es obligatorio.';
}
if (!$privacidad) {
    $errors[] = 'Debes aceptar la política de privacidad.';
}

if (count($errors) > 0) {
    $message = implode(' ', $errors);
    sendJson(['success' => false, 'message' => $message], 422);
}

$mail = new PHPMailer(true);
try {
    $mail->CharSet    = 'UTF-8';
    $mail->Encoding   = 'base64';
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USERNAME;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port       = (int) SMTP_PORT;

    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
    $mail->addAddress(DESTINATION_EMAIL, DESTINATION_NAME);
    $mail->addReplyTo($email, $nombre . ($apellidos !== '' ? ' ' . $apellidos : ''));

    $asuntoLabel = ASUNTO_LABELS[$asunto] ?? $asunto;
    $mail->Subject = '[Don José Contacto] ' . $asuntoLabel . ' — ' . $nombre;

    $body = "Has recibido un mensaje desde el formulario de contacto de Don José.\n\n";
    $body .= "Nombre: " . $nombre . "\n";
    if ($apellidos !== '') {
        $body .= "Apellidos: " . $apellidos . "\n";
    }
    $body .= "Email: " . $email . "\n";
    if ($telefono !== '') {
        $body .= "Teléfono: " . $telefono . "\n";
    }
    $body .= "Asunto: " . $asuntoLabel . "\n\n";
    $body .= "Mensaje:\n" . $mensaje . "\n";

    $mail->Body = $body;
    $mail->isHTML(false);
    $mail->send();
} catch (PHPMailerException $e) {
    error_log('[DonJose Form] ' . $e->getMessage());
    sendJson(['success' => false, 'message' => 'Error al enviar. Inténtalo más tarde.'], 500);
} catch (Throwable $e) {
    error_log('[DonJose Form] Error no controlado: ' . $e->getMessage());
    sendJson(['success' => false, 'message' => 'Error técnico temporal. Inténtalo más tarde.'], 500);
}
sendJson(['success' => true, 'message' => 'Mensaje enviado correctamente.'], 200);
