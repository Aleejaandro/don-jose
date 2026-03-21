<?php
/**
 * Configuración SMTP y formulario — Productos Don José
 * Completar con los datos reales en el servidor. No subir credenciales a repos públicos.
 */

declare(strict_types=1);

if (!defined('DMONDO_CONTACT')) {
    http_response_code(403);
    exit;
}

// ——— SMTP (Hostalia) ———
define('SMTP_HOST',       'cambiar-por-smtp-hostalia');
define('SMTP_PORT',       587);
define('SMTP_SECURE',     'tls');
define('SMTP_USERNAME',   'cambiar-por-email-real@dominio.com');
define('SMTP_PASSWORD',   'cambiar-por-password-smtp');

// ——— Remitente (From) ———
define('SMTP_FROM_EMAIL', 'cambiar-por-email-real@dominio.com');
define('SMTP_FROM_NAME',  'Don José — Web');

// ——— Destinatario de los mensajes del formulario ———
define('DESTINATION_EMAIL', 'info@productosdonjose.es');
define('DESTINATION_NAME',  'Don José');

define('RATE_LIMIT_ENABLED', false);
define('RATE_LIMIT_MAX_PER_HOUR', 5);
