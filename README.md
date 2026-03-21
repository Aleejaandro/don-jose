# Don José — Web estática

Sitio estático HTML, CSS y JS para Productos Don José. Rutas relativas para funcionar en GitHub Pages o cualquier servidor.

## Cómo correr la web

Abre la carpeta con un servidor local:

```bash
npx serve .
```

O con Python:

```bash
python -m http.server 8080
```

Luego abre en el navegador: `http://localhost:3000` (serve) o `http://localhost:8080` (Python).

## Estructura

- `index.html` — Inicio
- `marca.html`, `productos.html`, `recetas.html`, `contacto.html` — Secciones
- `productos/*.html` — Fichas de producto
- `recetas/*.html` — Páginas de cada receta (enlazadas desde recetas.html)
- `legal/` — Aviso legal, privacidad, cookies
- `css/styles.css` — Estilos
- `js/script.js` — Lógica
- `assets/logos/` — Logo
- `assets/img/home/` — Imágenes de la home (hero, lifestyle)
- `assets/img/productos/` — Imágenes de productos
- `assets/img/recetas/` — Imágenes de recetas
- `send-contact.php`, `send-contact-config.php` — Formulario de contacto (misma convención que otras webs del cliente). `js/site-config.js` → `ABM_SITE.formAction`. Ver **FORMULARIOS-HOSTALIA.md**.
- `php/` — PHPMailer (`composer install` en `php/` o `php/lib/phpmailer`).
- `sitemap.xml` — Mapa del sitio para buscadores (URLs absolutas; si cambias de dominio, edita la base).
- `robots.txt` — Indica a los robots qué rastrear y la URL del sitemap.
- `.htaccess` — Reglas Apache (UTF-8, caché, sin listado de directorios; opción HTTPS comentada).
