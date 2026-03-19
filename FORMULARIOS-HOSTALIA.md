# Formulario de contacto — Integración Hostalia

## Resumen

- **Formularios en el proyecto:** 1 (formulario de contacto en `contacto.html`).
- **Backend:** PHP con PHPMailer y SMTP autenticado.
- **Procesador:** `php/procesar-formulario.php` (único script; reutilizable si se añaden más formularios).

---

## 1. Formulario encontrado

| Ubicación      | Campos                                                                 | method/action actual                         | Validación JS      | Estado antes |
|----------------|------------------------------------------------------------------------|----------------------------------------------|--------------------|--------------|
| `contacto.html` | nombre*, apellidos, email*, telefono, asunto*, mensaje*, privacidad*  | Sin method/action (solo JS con preventDefault) | Sí (required, asunto) | Desconectado; solo simulación de envío |

\* Obligatorios

---

## 2. Cambios realizados

### 2.1 `contacto.html`

- Añadido `method="post"` y `action="php/procesar-formulario.php"` al `<form>`.
- Añadido **honeypot** anti-spam: campo oculto `web_site` (no visible para usuarios; los bots suelen rellenarlo).
- Enlace "Política de Privacidad" corregido: `href="legal/privacidad.html"`.
- Añadido bloque para mensaje de error: `<div class="form-error" id="form-error">` con `id="form-error-text"` para el texto.

### 2.2 `js/script.js`

- **Envío:** Se mantiene `preventDefault` y validación en cliente; el envío se hace por `fetch()` a `php/procesar-formulario.php` con `FormData` y cabecera `X-Requested-With: XMLHttpRequest` para que el PHP responda en JSON.
- **Respuesta:** Si `success: true` → se oculta el formulario y se muestra el mensaje de éxito; si no → se muestra `form-error` con el mensaje devuelto por el servidor (o mensaje genérico de error/conexión).
- **Redirección:** Si el usuario llega con `?enviado=1` o `?error=1` (envío tradicional sin JS o tras redirección del PHP), se muestra éxito o error y se limpia la URL con `history.replaceState`.

### 2.3 `css/styles.css`

- Añadida clase `.form-error` para el mensaje de error (fondo y borde en tono rojo), coherente con el diseño actual.

### 2.4 Archivos nuevos

- **`php/config.php`**  
  Configuración SMTP y destinatario. Hay que rellenar los valores reales cuando tengas acceso a Hostalia (ver sección 4).

- **`php/procesar-formulario.php`**  
  - Solo acepta POST.  
  - Honeypot: si `web_site` viene rellenado, se considera bot y no se envía email (pero se responde “éxito”).  
  - Comprueba envíos vacíos (nombre, email, mensaje vacíos) y rechaza.  
  - Valida y sanea: nombre, apellidos, email, teléfono, asunto, mensaje, aceptación de privacidad.  
  - Envía el correo con PHPMailer por SMTP autenticado.  
  - Si la petición es Ajax → responde JSON. Si no → redirige a `contacto.html?enviado=1` o `?error=1`.

- **`php/composer.json`**  
  Dependencia: `phpmailer/phpmailer` ^6.9. En el servidor (o en local) hay que ejecutar `composer install` dentro de `php/` para generar `php/vendor/`.

---

## 3. Estructura de archivos relevante

```
/
├── contacto.html
├── css/
│   └── styles.css
├── js/
│   └── script.js
├── php/
│   ├── config.php              ← Rellenar con datos Hostalia
│   ├── procesar-formulario.php
│   ├── composer.json
│   └── vendor/                 ← Generado con composer install
│       └── ...
└── FORMULARIOS-HOSTALIA.md     ← Esta guía
```

---

## 4. Qué rellenar cuando tengas acceso a Hostalia

Edita **`php/config.php`** y sustituye los placeholders por los datos que te proporcione Hostalia (o los que uses para SMTP):

| Constante            | Ejemplo / descripción |
|----------------------|------------------------|
| `SMTP_HOST`          | Servidor SMTP (ej. `smtp.dominio.com`, `mail.dominio.com` o el que indique Hostalia). |
| `SMTP_PORT`          | Normalmente `587` (TLS) o `465` (SSL). |
| `SMTP_SECURE`        | `'tls'` o `'ssl'` según el puerto. |
| `SMTP_USERNAME`      | Email de la cuenta de correo (ej. `info@tudominio.com`). |
| `SMTP_PASSWORD`      | Contraseña de esa cuenta. |
| `SMTP_FROM_EMAIL`    | Mismo que `SMTP_USERNAME` (o el que quieras como remitente). |
| `SMTP_FROM_NAME`     | Nombre que verá el destinatario (ej. `Don José — Web`). |
| `DESTINATION_EMAIL`  | Email donde quieres recibir los mensajes del formulario (ej. `info@donjose.es`). |
| `DESTINATION_NAME`   | Nombre del destinatario (ej. `Don José`). |

No subas `config.php` con contraseñas reales a repositorios públicos. En producción puedes dejarlo fuera de Git o usar variables de entorno y leerlas desde PHP.

---

## 5. Cómo cambiar el email destinatario

En **`php/config.php`** cambia:

- **`DESTINATION_EMAIL`**: dirección donde llegan los contactos.
- **`DESTINATION_NAME`**: nombre que aparece en el destinatario (opcional, solo visual).

No hace falta tocar el HTML ni el JS.

---

## 6. Instalación de PHPMailer (Composer)

En el servidor (o en tu máquina y luego subiendo la carpeta):

```bash
cd php
composer install --no-interaction
```

Se creará `php/vendor/` con PHPMailer. Sube toda la carpeta `php/` (incluido `vendor/`) a Hostalia.

Si en Hostalia no tienes Composer, ejecuta `composer install` en tu PC dentro de `php/` y sube el proyecto con la carpeta `php/vendor/` ya generada.

---

## 7. Cómo probar al subir a Hostalia

1. Sube todos los archivos (HTML, CSS, JS, `php/` con `config.php` ya configurado y `vendor/`).
2. Asegúrate de que la URL del sitio sea la correcta (ej. `https://tudominio.com`).
3. Entra en la página de contacto y rellena el formulario con datos reales (email válido, asunto, mensaje, privacidad marcada).
4. Envía:
   - Deberías ver el mensaje de éxito en la misma página (envío vía Ajax).
   - Debería llegarte el correo a la cuenta configurada en `DESTINATION_EMAIL`.
5. Prueba sin JavaScript (o con “Enviar” haciendo submit clásico): deberías ser redirigido a `contacto.html?enviado=1` y ver el mensaje de éxito.
6. Prueba validación: quita un campo obligatorio o marca solo privacidad sin mensaje; debe mostrarse error en cliente o, si bypaseas el cliente, error desde el servidor (mensaje o redirección `?error=1`).

---

## 8. Limitaciones típicas en Hostalia

- **SMTP:** Algunos planes solo permiten envío desde el mismo dominio. Usa una cuenta de correo del propio dominio (ej. `info@tudominio.com`) como `SMTP_USERNAME` y `SMTP_FROM_EMAIL`.
- **Puertos:** Si 587 no funciona, prueba 465 con `SMTP_SECURE = 'ssl'`. Si el hosting bloquea SMTP, tendrás que usar el método que ofrezca Hostalia (relay, etc.).
- **PHP:** El script requiere PHP 7.4+ (recomendable 8.x). Comprueba la versión en el panel de Hostalia.
- **Rutas:** El `action` del formulario es `php/procesar-formulario.php`. Si en Hostalia la web está en un subdirectorio (ej. `public_html/donjose/`), puede que tengas que usar una ruta absoluta o ajustar la ruta en el `action` y en las redirecciones del PHP (en ese caso, en `procesar-formulario.php` las redirecciones podrían ser por ejemplo `../contacto.html` o la ruta que corresponda a tu instalación).

---

## 9. Protección anti-spam implementada

- **Honeypot:** Campo `web_site` oculto; si viene rellenado, no se envía correo (pero se responde éxito al cliente).
- **Validación en servidor:** Campos obligatorios, formato de email y aceptación de privacidad.
- **Rechazo de envíos vacíos:** Si nombre, email y mensaje están vacíos, no se envía.
- Opcional: en `config.php` está definido `RATE_LIMIT_ENABLED` (desactivado); se puede activar y usar para limitar envíos por IP si el hosting lo permite.

Con esto el formulario queda preparado para integrarse en Hostalia rellenando solo `php/config.php` y ejecutando `composer install` en `php/`.
