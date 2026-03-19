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
- `php/` — Backend del formulario de contacto (PHP + PHPMailer + SMTP). Ver **FORMULARIOS-HOSTALIA.md** para configuración en Hostalia.
