# Productos Don Jose — Web estatica

Proyecto web estatico (HTML/CSS/JS) con formulario de contacto en PHP.

Canonica final del proyecto:

- `https://productosdonjose.es`

La variante con `www` debe redirigir a esta URL.

## Estructura tecnica

- `index.html`, `marca.html`, `productos.html`, `recetas.html`, `contacto.html`
- `productos/*.html` y `recetas/*.html`
- `legal/*.html`
- `css/styles.css`
- `js/script.js`
- `js/site-config.js` 
- `send-contact.php` (logica del formulario)
- `send-contact-config.php` (credenciales SMTP y destinatario real)
- `send-contact-config.sample.php` (plantilla para produccion)
- `.htaccess` (HTTPS, canonia sin `www`, cache, compresion, proteccion config)
- `robots.txt`
- `sitemap.xml`

## Formulario de contacto (PHP + PHPMailer + SMTP)

El formulario usa:

- `send-contact.php` como endpoint
- PHPMailer con SMTP autenticado
- configuracion sensible separada en `send-contact-config.php`
- respuesta **siempre JSON** (`success` + `message`)
- gestion de exito/error en frontend con `fetch` (`js/script.js`)
- redireccion en frontend al exito (`?enviado=1`)

### Campos esperados por backend

- `nombre` (obligatorio)
- `apellidos` (opcional)
- `email` (obligatorio)
- `telefono` (opcional)
- `asunto` (obligatorio)
- `mensaje` (obligatorio)
- `privacidad` (obligatorio)
- `web_site` (honeypot anti-spam, oculto)

## PHPMailer

Si no encuentra PHPMailer, devuelve error controlado y registra un mensaje en `error_log`.

## Configuracion de hosting (pasos del administrador)

1. Subir todo el proyecto al `public_html` (o raiz web).
2. Completar `send-contact-config.php` con datos reales:
   - `SMTP_HOST`
   - `SMTP_PORT`
   - `SMTP_SECURE`
   - `SMTP_USERNAME`
   - `SMTP_PASSWORD`
   - `SMTP_FROM_EMAIL`
   - `SMTP_FROM_NAME`
   - `DESTINATION_EMAIL`
   - `DESTINATION_NAME`
3. Confirmar que PHPMailer esta disponible:
   - opcion principal: `lib/phpmailer/src/`
   - opcion alternativa: `vendor/autoload.php`
4. Verificar que `.htaccess` esta activo (`AllowOverride` habilitado).
5. Verificar redireccion:
   - `http://productosdonjose.es` -> `https://productosdonjose.es`
   - `https://www.productosdonjose.es` -> `https://productosdonjose.es`

## Verificacion antes de publicar

- Enviar formulario real desde `contacto.html`
- Comprobar recepcion del email en `DESTINATION_EMAIL`
- Validar que la respuesta de error no expone datos sensibles
- Confirmar que `send-contact.php` devuelve siempre JSON y no usa redirecciones HTTP
- Comprobar que `send-contact-config.php` no es accesible por navegador



