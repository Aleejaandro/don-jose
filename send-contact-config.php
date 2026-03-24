<?php
/**
 * Configuración SMTP y formulario — Productos Don José
 * Completar con los datos reales en el servidor. No subir credenciales a repos públicos.
 */

declare(strict_types=1);

if (!defined('DONJOSE_CONTACT')) {
    http_response_code(403);
    exit;
}

// ——— SMTP (Hostalia) ———
define('SMTP_HOST',       'RELLENAR_SMTP_HOST');
define('SMTP_PORT',       587);
define('SMTP_SECURE',     'tls');
define('SMTP_USERNAME',   'RELLENAR_SMTP_USERNAME');
define('SMTP_PASSWORD',   'RELLENAR_SMTP_PASSWORD');

// ——— Remitente (From) ———
define('SMTP_FROM_EMAIL', 'RELLENAR_SMTP_FROM_EMAIL');
define('SMTP_FROM_NAME',  'Don José — Web');

// ——— Destinatario de los mensajes del formulario ———
define('DESTINATION_EMAIL', 'RELLENAR_DESTINATION_EMAIL');
define('DESTINATION_NAME',  'Productos Don José');

