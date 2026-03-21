<?php
/**
 * Plantilla — copiar a send-contact-config.php en el servidor y rellenar.
 * Este archivo puede bloquearse en .htaccess igual que la config real.
 */

declare(strict_types=1);

if (!defined('DMONDO_CONTACT')) {
    http_response_code(403);
    exit;
}

define('SMTP_HOST',       '');
define('SMTP_PORT',       587);
define('SMTP_SECURE',     'tls');
define('SMTP_USERNAME',   '');
define('SMTP_PASSWORD',   '');
define('SMTP_FROM_EMAIL', '');
define('SMTP_FROM_NAME',  'Nombre sitio — Web');
define('DESTINATION_EMAIL', '');
define('DESTINATION_NAME',  'Nombre sitio');
define('RATE_LIMIT_ENABLED', false);
define('RATE_LIMIT_MAX_PER_HOUR', 5);
