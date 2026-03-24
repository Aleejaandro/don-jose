/**
 * Configuración front — Productos Don José.
 */
window.DONJOSE_SITE = {
  formAction: 'send-contact.php',
  canonicalBase: 'https://productosdonjose.es/',
  ogImage: 'https://productosdonjose.es/assets/logos/logo-don-jose.png',
  /**
   * Home — zona productos (index.html):
   * true  → escaparate editorial (una referencia / nueva línea).
   * false → rejilla clásica de 4 productos destacados con enlace a fichas.
   */
  homeUseEditorialProductShowcase: true,
  /**
   * Página productos (productos.html):
   * true  → modo editorial "línea en desarrollo" (sin catálogo visible).
   * false → modo catálogo completo con filtros y fichas (estado original).
   */
  productsUseEditorialMode: true,
  /**
   * Recetas (recetas/*.html):
   * true  → mostrar bloque "Producto utilizado".
   * false → ocultarlo temporalmente hasta disponer de catálogo definitivo.
   */
  recipesShowUsedProduct: false
};
