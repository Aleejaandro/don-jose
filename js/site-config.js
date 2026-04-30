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
   * true  → modo editorial: bloque destacado arriba (sin catálogo visible debajo).
   * false → modo catálogo completo con filtros y rejilla.
   */
  productsUseEditorialMode: false,
  /**
   * Catálogo (productos.html + rejilla de productos en index.html):
   * true  → cada tarjeta enlaza a la ficha HTML; overlay "Ver producto".
   * false → solo catálogo informativo: sin navegación a fichas ni CTA (incl. conservas en frasco/lata; las páginas siguen en el repo).
   *
   * Filtros en productos.html: data-tags en cada .catalog-card (legumbres, alubias, lentejas,
   * garbanzos, arroces, ecologico, conserva, seleccion). Ecológico/conserva: añadir esas
   * palabras a data-tags cuando el producto corresponda.
   */
  productsCatalogDetailLinksEnabled: false,
  /**
   * Recetas (recetas/*.html):
   * true  → mostrar bloque "Producto utilizado".
   * false → ocultarlo temporalmente hasta disponer de catálogo definitivo.
   */
  recipesShowUsedProduct: false
};

(function () {
  var site = window.DONJOSE_SITE || {};
  var on = site.productsCatalogDetailLinksEnabled === true;
  document.documentElement.dataset.productsCatalogDetailLinks = on ? 'on' : 'off';

  function applyCatalogLinkAccessibility() {
    if (on) return;
    document.querySelectorAll('.catalog-card .card-full-link, .product-card .card-full-link').forEach(function (el) {
      el.setAttribute('tabindex', '-1');
      el.setAttribute('aria-hidden', 'true');
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', applyCatalogLinkAccessibility);
  } else {
    applyCatalogLinkAccessibility();
  }
})();
