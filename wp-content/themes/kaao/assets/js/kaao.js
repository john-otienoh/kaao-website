/**
 * KAAO — progressive enhancement bundle.
 *
 * Everything here is optional: the site is fully navigable, readable and
 * submittable with JavaScript disabled. Each module bails out early when its
 * markup is absent, so this single deferred file stays cheap on every page.
 *
 * Modules: sticky header · mobile drawer · submenu keyboard support ·
 * hero slider · member directory filters · gallery photo slider · smooth
 * anchor offset.
 */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  /* ---------------------------------------------------------------- utils */
  function on(el, evt, fn, opts) { if (el) el.addEventListener(evt, fn, opts); }
  function $(sel, root) { return (root || document).querySelector(sel); }
  function $$(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

  /* ------------------------------------------------- 1. sticky header state */
  (function stickyHeader() {
    var header = $('.kaao-header');
    if (!header) return;
    var ticking = false;
    function update() {
      header.classList.toggle('is-stuck', window.scrollY > 8);
      ticking = false;
    }
    on(window, 'scroll', function () {
      if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    update();
  })();

  /* ------------------------------------------------------ 2. mobile drawer */
  (function drawer() {
    var toggle = $('.kaao-burger');
    var panel  = $('#kaao-drawer');
    var scrim  = $('.kaao-scrim');
    if (!toggle || !panel) return;

    var lastFocused = null;

    function open() {
      lastFocused = document.activeElement;
      panel.hidden = false;
      // Next frame so the transition runs from the closed transform.
      window.requestAnimationFrame(function () {
        panel.classList.add('is-open');
        if (scrim) { scrim.hidden = false; scrim.classList.add('is-open'); }
      });
      toggle.setAttribute('aria-expanded', 'true');
      document.body.classList.add('is-locked');
      var first = $('.kaao-drawer__close', panel) || $(FOCUSABLE, panel);
      if (first) first.focus();
    }

    function close(restoreFocus) {
      panel.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('is-locked');
      if (scrim) scrim.classList.remove('is-open');
      window.setTimeout(function () {
        if (!panel.classList.contains('is-open')) {
          panel.hidden = true;
          if (scrim) scrim.hidden = true;
        }
      }, reduceMotion.matches ? 0 : 240);
      if (restoreFocus !== false && lastFocused) lastFocused.focus();
    }

    on(toggle, 'click', function () {
      panel.classList.contains('is-open') ? close() : open();
    });
    on(scrim, 'click', function () { close(); });
    $$('.kaao-drawer__close', panel).forEach(function (b) { on(b, 'click', function () { close(); }); });

    on(document, 'keydown', function (e) {
      if (!panel.classList.contains('is-open')) return;
      if (e.key === 'Escape') { e.preventDefault(); close(); return; }
      if (e.key !== 'Tab') return;
      // Trap focus inside the drawer.
      var items = $$(FOCUSABLE, panel).filter(function (el) { return el.offsetParent !== null; });
      if (!items.length) return;
      var first = items[0], last = items[items.length - 1];
      if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });

    // Close when we cross into the desktop breakpoint.
    var desktop = window.matchMedia('(min-width: 64rem)');
    var onChange = function (e) { if (e.matches && panel.classList.contains('is-open')) close(false); };
    desktop.addEventListener ? desktop.addEventListener('change', onChange) : desktop.addListener(onChange);

    /* Drawer submenu disclosures */
    $$('.kaao-subtoggle', panel).forEach(function (btn) {
      on(btn, 'click', function () {
        var sub = document.getElementById(btn.getAttribute('aria-controls'));
        if (!sub) return;
        var expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!expanded));
        sub.hidden = expanded;
      });
    });
  })();

  /* --------------------------------- 3. desktop submenu keyboard/escape help */
  (function desktopSubmenus() {
    $$('.kaao-nav li.menu-item-has-children, .kaao-nav li.has-children').forEach(function (li) {
      on(li, 'keydown', function (e) {
        if (e.key !== 'Escape') return;
        var link = $('a', li);
        if (link) link.focus();
      });
    });
  })();

  /* ----------------------------------------------------- 4. hero slider */
  (function heroSlider() {
    var root = $('[data-hero-slider]');
    if (!root) return;
    var slides = $$('.hero__slide', root);
    var dots   = $$('.hero__dot', root);
    if (slides.length < 2) return;

    var index = 0;
    var timer = null;
    var delay = parseInt(root.getAttribute('data-interval'), 10) || 7000;

    function show(i) {
      index = (i + slides.length) % slides.length;
      slides.forEach(function (s, n) {
        s.classList.toggle('is-active', n === index);
        s.setAttribute('aria-hidden', String(n !== index));
      });
      dots.forEach(function (d, n) { d.setAttribute('aria-current', String(n === index)); });
    }

    function start() {
      if (reduceMotion.matches || timer) return;
      timer = window.setInterval(function () { show(index + 1); }, delay);
    }
    function stop() { window.clearInterval(timer); timer = null; }

    dots.forEach(function (dot, n) {
      on(dot, 'click', function () { stop(); show(n); start(); });
    });

    on(root, 'keydown', function (e) {
      if (e.key === 'ArrowRight') { stop(); show(index + 1); }
      else if (e.key === 'ArrowLeft') { stop(); show(index - 1); }
    });
    on(root, 'mouseenter', stop);
    on(root, 'mouseleave', start);
    on(root, 'focusin', stop);
    on(root, 'focusout', start);
    on(document, 'visibilitychange', function () { document.hidden ? stop() : start(); });

    show(0);
    start();
  })();

  /* ---------------------------------------------- 5. hero video (mode: video) */
  (function heroVideo() {
    var video = $('[data-hero-video]');
    if (!video) return;

    // Never pull the video on small screens or when motion is reduced — the
    // poster image is already painted and is the LCP element there.
    var wantsVideo = window.matchMedia('(min-width: 48rem)').matches && !reduceMotion.matches;
    if (!wantsVideo) { video.remove(); return; }

    // Respect the Save-Data hint and known-slow connections.
    var conn = navigator.connection;
    if (conn && (conn.saveData || /^([23]g|slow-2g)$/.test(conn.effectiveType || ''))) {
      video.remove();
      return;
    }

    $$('source[data-src]', video).forEach(function (s) {
      s.src = s.getAttribute('data-src');
      s.removeAttribute('data-src');
    });
    video.load();
    var p = video.play();
    if (p && typeof p.catch === 'function') { p.catch(function () { /* autoplay blocked — poster stands in */ }); }
  })();

  /* ------------------------------------------- 6. directory filter niceties */
  (function directoryFilters() {
    var form = $('[data-filter-form]');
    if (!form) return;

    // Auto-submit on select change (the Search button remains for no-JS users).
    $$('select', form).forEach(function (sel) {
      on(sel, 'change', function () { form.submit(); });
    });

    // Drop empty params so the resulting URL stays clean and shareable.
    on(form, 'submit', function () {
      $$('select, input', form).forEach(function (el) {
        if (!el.value) el.disabled = true;
      });
    });
  })();

  /* ------------------------------------------------- 7. gallery photo slider */
  (function photoSlider() {
    $$('[data-photo-slider]').forEach(function (root) {
      var slides = $$('.photo-slider__slide', root);
      if (slides.length < 2) return;

      var dots = $$('.photo-slider__dot', root);
      var prev = $('.photo-slider__prev', root);
      var next = $('.photo-slider__next', root);
      var index = 0;

      function show(i) {
        index = (i + slides.length) % slides.length;
        slides.forEach(function (s, n) {
          s.classList.toggle('is-active', n === index);
          s.setAttribute('aria-hidden', String(n !== index));
        });
        dots.forEach(function (d, n) { d.setAttribute('aria-current', String(n === index)); });
      }

      dots.forEach(function (dot, n) { on(dot, 'click', function () { show(n); }); });
      on(prev, 'click', function () { show(index - 1); });
      on(next, 'click', function () { show(index + 1); });

      root.classList.add('is-ready');
      show(0);
    });
  })();

  /* -------------------------------------- 8. keep anchors clear of the header */
  (function anchorOffset() {
    if (!location.hash) return;
    var target = document.getElementById(location.hash.slice(1));
    if (!target) return;
    window.setTimeout(function () {
      var header = $('.kaao-header');
      var offset = (header ? header.offsetHeight : 0) + 16;
      var top = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top: top, behavior: reduceMotion.matches ? 'auto' : 'smooth' });
    }, 0);
  })();

})();

/* ----------------------------------------------- 9. stats count-up */
(function statsCountUp() {
  'use strict';

  function $(sel, root) { return (root || document).querySelector(sel); }
  function $$(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  var section = $('[data-stats-section]');
  if (!section) return;

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  var cards        = $$('[data-stat-card]', section);
  var valueEls     = $$('.stat__value[data-count]', section);
  if (!cards.length) return;

  var DURATION = 1800; // ms — total count-up time
  var STAGGER  = 130;  // ms — delay between each card
  var EASE     = function (t) { return 1 - Math.pow(1 - t, 3); }; // cubic ease-out

  function formatNumber(n, suffix) {
    // en-KE gives comma separators (e.g. 1,500) matching KAAO's English content.
    return n.toLocaleString('en-KE') + suffix;
  }

  // Immediately swap final numbers for 0 so there is no flash of the target
  // value before the observer fires.
  valueEls.forEach(function (el) {
    var suffix = el.getAttribute('data-suffix') || '';
    el.textContent = formatNumber(0, suffix);
  });

  // Mark the section so CSS knows JS is active.
  section.classList.add('js-count-up');

  function animateValue(el, target, suffix) {
    var startTime = null;

    function step(timestamp) {
      if (!startTime) startTime = timestamp;

      var progress = Math.min((timestamp - startTime) / DURATION, 1);
      var eased    = EASE(progress);
      var current  = Math.floor(eased * target);

      el.textContent = formatNumber(current, suffix);

      if (progress < 1) {
        window.requestAnimationFrame(step);
      } else {
        el.textContent = formatNumber(target, suffix);
      }
    }

    window.requestAnimationFrame(step);
  }

  function triggerAnimations() {
    cards.forEach(function (card, i) {
      var delay = i * STAGGER;

      // Staggered card entrance (fade + slide up)
      window.setTimeout(function () {
        card.classList.add('is-visible');
      }, delay);

      // Staggered number count-up (starts slightly after card begins appearing)
      var valueEl = $('.stat__value[data-count]', card);
      if (valueEl) {
        var target = parseInt(valueEl.getAttribute('data-count'), 10);
        var suffix = valueEl.getAttribute('data-suffix') || '';
        window.setTimeout(function () {
          animateValue(valueEl, target, suffix);
        }, delay + 80);
      }
    });
  }

  // Reduced motion: reveal final values instantly, no counting.
  if (reduceMotion.matches) {
    valueEls.forEach(function (el) {
      var target = parseInt(el.getAttribute('data-count'), 10);
      var suffix = el.getAttribute('data-suffix') || '';
      el.textContent = formatNumber(target, suffix);
    });
    cards.forEach(function (card) {
      card.classList.add('is-visible');
    });
    return;
  }

  // Start immediately as the primary fallback. Observer-based triggering is a
  // non-blocking enhancement only; it should never prevent the counter from
  // running when the section is already visible or the browser misses the event.
  var hasTriggered = false;
  function startIfVisible() {
    if (hasTriggered) return;
    hasTriggered = true;
    triggerAnimations();
  }

  function startAfterLoad() {
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
      window.setTimeout(startIfVisible, 120);
      return;
    }
    window.addEventListener('DOMContentLoaded', function () {
      window.setTimeout(startIfVisible, 120);
    }, { once: true });
  }

  if (!('IntersectionObserver' in window)) {
    startAfterLoad();
    return;
  }

  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        startIfVisible();
        observer.disconnect();
      }
    });
  }, {
    threshold: 0.15,
    rootMargin: '0px 0px -32px 0px'
  });

  observer.observe(section);
  startAfterLoad();
})();
