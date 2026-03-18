/* =============================================================
   PRODUCTOS DON JOSÉ — script.js
   Vanilla JS: nav scroll, mobile menu, fade-in, filters, form
   ============================================================= */

document.addEventListener('DOMContentLoaded', () => {

  /* ---- Footer year ---- */
  document.querySelectorAll('.footer-year').forEach(el => {
    el.textContent = new Date().getFullYear();
  });

  /* ---- Navbar: scroll effect ---- */
  const header = document.getElementById('site-header');
  if (header) {
    const onScroll = () => {
      header.classList.toggle('scrolled', window.scrollY > 30);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---- Mobile menu ---- */
  const hamburger  = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobile-menu');
  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => {
      const isOpen = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', isOpen);
      hamburger.setAttribute('aria-expanded', isOpen);
    });
    mobileMenu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        mobileMenu.classList.remove('open');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', false);
      });
    });
  }

  /* ---- Fade-in on scroll ---- */
  const fadeEls = document.querySelectorAll('.fade-in');
  if ('IntersectionObserver' in window && fadeEls.length) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el    = entry.target;
        const delay = parseInt(el.dataset.delay || '0', 10);
        setTimeout(() => el.classList.add('visible'), delay);
        observer.unobserve(el);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    fadeEls.forEach(el => observer.observe(el));
  } else {
    fadeEls.forEach(el => el.classList.add('visible'));
  }

  /* ---- Smooth scroll for anchor links (home page) ---- */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', (e) => {
      const href = anchor.getAttribute('href');
      if (!href || href === '#') return;
      const target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      const headerH = header ? header.offsetHeight : 0;
      const top = target.getBoundingClientRect().top + window.scrollY - headerH - 12;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  });

  /* ---- Product filter (productos.html) ---- */
  const productTabs = document.getElementById('filter-tabs');
  if (productTabs) {
    const buttons   = productTabs.querySelectorAll('.filter-btn');
    const cards     = document.querySelectorAll('.catalog-card');
    const sections  = document.querySelectorAll('#legumbres-section, #arroces-section');

    const applyProductFilter = (filter) => {
      buttons.forEach(b => {
        const active = b.dataset.filter === filter;
        b.classList.toggle('active', active);
        b.setAttribute('aria-selected', active);
      });
      cards.forEach(card => {
        const cats = (card.dataset.category || '').split(' ');
        card.classList.toggle('hidden', filter !== 'todos' && !cats.includes(filter));
      });
      sections.forEach(section => {
        const visible = section.querySelectorAll('.catalog-card:not(.hidden)').length > 0;
        section.style.display = visible ? '' : 'none';
      });
    };

    productTabs.addEventListener('click', e => {
      const btn = e.target.closest('.filter-btn');
      if (btn) applyProductFilter(btn.dataset.filter);
    });
  }

  /* ---- Recipe filter (recetas.html) ---- */
  const recipeTabs = document.getElementById('recipe-filter-tabs');
  if (recipeTabs) {
    const buttons   = recipeTabs.querySelectorAll('.filter-btn');
    const cards     = document.querySelectorAll('.recipe-card-photo');
    const noResults = document.getElementById('no-results');

    const applyRecipeFilter = (filter) => {
      buttons.forEach(b => {
        const active = b.dataset.filter === filter;
        b.classList.toggle('active', active);
        b.setAttribute('aria-selected', active);
      });
      let count = 0;
      cards.forEach(card => {
        const cats = (card.dataset.recipe || '').split(' ');
        const show = filter === 'todas' || cats.includes(filter);
        card.classList.toggle('hidden', !show);
        if (show) count++;
      });
      if (noResults) noResults.style.display = count === 0 ? 'block' : 'none';
    };

    recipeTabs.addEventListener('click', e => {
      const btn = e.target.closest('.filter-btn');
      if (btn) applyRecipeFilter(btn.dataset.filter);
    });
  }

  /* ---- Contact form (contacto.html) ---- */
  const form      = document.getElementById('contact-form');
  const success   = document.getElementById('form-success');
  const submitBtn = document.getElementById('submit-btn');
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const required = form.querySelectorAll('[required]');
      let valid = true;

      required.forEach(field => {
        field.style.borderColor = '';
        const empty = field.type === 'checkbox' ? !field.checked : !field.value.trim();
        if (empty) {
          field.style.borderColor = '#e53e3e';
          valid = false;
        }
      });

      if (!valid) return;

      submitBtn.disabled = true;
      submitBtn.textContent = 'Enviando…';
      setTimeout(() => {
        form.style.display = 'none';
        if (success) success.style.display = 'flex';
      }, 900);
    });

    form.addEventListener('input', e => {
      if (e.target.style) e.target.style.borderColor = '';
    });
  }

});
