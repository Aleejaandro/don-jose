<?php
/**
 * Plantilla — copiar a send-contact-config.php en el servidor y rellenar.
 * Este archivo puede bloquearse en .htaccess igual que la config real.
 */

declare(strict_types=1);

if (!defined('DONJOSE_CONTACT')) {
    http_response_code(403);
    exit;
}

define('SMTP_HOST',       'RELLENAR_SMTP_HOST');
define('SMTP_PORT',       587);
define('SMTP_SECURE',     'tls');
define('SMTP_USERNAME',   'RELLENAR_SMTP_USERNAME');
define('SMTP_PASSWORD',   'RELLENAR_SMTP_PASSWORD');
define('SMTP_FROM_EMAIL', 'RELLENAR_SMTP_FROM_EMAIL');
define('SMTP_FROM_NAME',  'Don José — Web');
define('DESTINATION_EMAIL', 'RELLENAR_DESTINATION_EMAIL');
define('DESTINATION_NAME',  'Productos Don José');
