<?php
/**
 * Configuración SMTP y formularios — Don José
 * Completar con los datos reales cuando tengas acceso a Hostalia.
 * No subir este archivo con credenciales reales a repositorios públicos.
 */

// ——— SMTP (Hostalia) ———
define('SMTP_HOST',       'cambiar-por-smtp-hostalia');           // Ej: smtp.dominio.com o mail.dominio.com
define('SMTP_PORT',       587);                                    // 587 TLS (recomendado) o 465 SSL
define('SMTP_SECURE',     'tls');                                  // 'tls' o 'ssl'
define('SMTP_USERNAME',   'cambiar-por-email-real@dominio.com');   // Email de la cuenta SMTP
define('SMTP_PASSWORD',   'cambiar-por-password-smtp');            // Contraseña de la cuenta

// ——— Remitente (From) ———
define('SMTP_FROM_EMAIL', 'cambiar-por-email-real@dominio.com');    // Suele ser el mismo que SMTP_USERNAME
define('SMTP_FROM_NAME',  'Don José — Web');

// ——— Destinatario de los mensajes del formulario ———
define('DESTINATION_EMAIL', 'info@donjose.es');                     // Email donde recibir los contactos
define('DESTINATION_NAME',  'Don José');

// ——— Opcional: límite de envíos por IP (anti-spam) ———
define('RATE_LIMIT_ENABLED', false);   // Activar en producción si Hostalia lo permite
define('RATE_LIMIT_MAX_PER_HOUR', 5);
