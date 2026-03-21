<?php
/**
 * Envío formulario de contacto — Productos Don José
 * POST (Ajax JSON o redirección). PHPMailer + SMTP.
 */

declare(strict_types=1);

define('DMONDO_CONTACT', true);
require __DIR__ . '/send-contact-config.php';

$autoload = __DIR__ . '/php/vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
} else {
    require_once __DIR__ . '/php/lib/phpmailer/src/Exception.php';
    require_once __DIR__ . '/php/lib/phpmailer/src/PHPMailer.php';
    require_once __DIR__ . '/php/lib/phpmailer/src/SMTP.php';
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

function sendJson(array $data): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function redirect(string $page, string $param, string $value): void
{
    $url = $page . '?' . $param . '=' . rawurlencode($value);
    header('Location: ' . $url, true, 303);
    exit;
}

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isAjax) {
        sendJson(['success' => false, 'message' => 'Método no permitido.']);
    }
    redirect('contacto.html', 'error', '1');
    exit;
}

if (trim((string) ($_POST['nombre'] ?? '')) === '' && trim((string) ($_POST['email'] ?? '')) === '' && trim((string) ($_POST['mensaje'] ?? '')) === '') {
    if ($isAjax) {
        sendJson(['success' => false, 'message' => 'Datos insuficientes.']);
    }
    redirect('contacto.html', 'error', '1');
    exit;
}

$honeypot = trim((string) ($_POST['web_site'] ?? ''));
if ($honeypot !== '') {
    if ($isAjax) {
        sendJson(['success' => true, 'message' => 'Mensaje recibido.']);
    }
    redirect('contacto.html', 'enviado', '1');
    exit;
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
    if ($isAjax) {
        sendJson(['success' => false, 'message' => $message]);
    }
    redirect('contacto.html', 'error', '1');
    exit;
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
    if ($isAjax) {
        sendJson(['success' => false, 'message' => 'Error al enviar. Inténtalo más tarde.']);
    }
    redirect('contacto.html', 'error', '1');
    exit;
}

if ($isAjax) {
    sendJson(['success' => true, 'message' => 'Mensaje enviado correctamente.']);
}
redirect('contacto.html', 'enviado', '1');
