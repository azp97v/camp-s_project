// Step by Step Theme System
// Supports: Light → Dark → Cosmic → Light

(function () {
  'use strict';

  const THEME_KEY = 'stepbystep-theme';

  function getLogoElement() {
    return document.querySelector('#main-logo, .logo-image, .site-logo, .brand-logo, .logo, .navbar-brand img');
  }

  function getThemeToggle() {
    return document.getElementById('theme-toggle');
  }

  const themeAssets = {
    light: {
      background: '/assets/img/remove%20the%20central%20c.png',
      logo: '/assets/img/crop%20the%20image%20to%20ke.png',
      icon: '🌙',
      label: 'Switch to dark mode',
      color: '#f5f7fb'
    },

    dark: {
      background: '/assets/img/Screenshot%202025-12-22%20232017.png',
      logo: '/assets/img/create%20a%20circular%20lo.png',
      icon: '☀️',
      label: 'Switch to light mode',
      color: '#0f1724'
    },

    cosmic: {
      background: null,
      // galaxy icon for cosmic theme (replace with your preferred galaxy image path)
      logo: '/assets/img/galaxy-logo.png',
      icon: '🪐',
      label: 'Switch to light mode',
      color: '#0d0f1a'
    }
  };

  function getTheme() {
    return localStorage.getItem(THEME_KEY) || 'light';
  }

  function getAllLogoImageElements() {
    return Array.from(document.querySelectorAll('#main-logo, .logo-image img, .site-logo img, .brand-logo img, .logo img, .navbar-brand img, img.logo'));
  }

  function isAuthPage() {
    const p = (location.pathname || '').toLowerCase();
    if (p.includes('/login') || p.includes('/register') || p.includes('/signup') || p.includes('/password') || p.includes('/reset') || p.includes('/forgot') || p.includes('/otp') || p.includes('/verify')) return true;
    if (document.querySelector('form[action*="login"], form[action*="register"], form[action*="password"], form[action*="otp"], form.login, form.register, form.forgot, form.otp, form.verify, .otp-form')) return true;
    return false;
  }

  function setTheme(theme) {
    if (!themeAssets[theme]) theme = 'light';

    localStorage.setItem(THEME_KEY, theme);
    document.documentElement.setAttribute('data-theme', theme);

    const themeConfig = themeAssets[theme];

    if (theme === 'cosmic') {
      document.body.style.backgroundImage = 'none';
      document.body.style.backgroundColor = themeConfig.color;
      generateCosmicBackground();
    } else {
      clearCosmicBackground();

      const bgPath = themeConfig.background;
      document.body.style.backgroundImage = `url('${bgPath}')`;
      document.body.style.backgroundSize = 'cover';
      document.body.style.backgroundPosition = 'center';
      document.body.style.backgroundRepeat = 'no-repeat';
      document.body.style.backgroundAttachment = 'fixed';

      const img = new Image();
      img.onerror = function () {
        document.body.style.backgroundImage = 'none';
        document.body.style.backgroundColor = themeConfig.color;
      };
      img.src = bgPath;
    }

    let logoElement = getLogoElement();
    if (logoElement) {
      // If we previously replaced the original logo, restore it when leaving cosmic
      if (theme !== 'cosmic' && window._replacedLogo) {
        const replaced = document.querySelector('.logo-image-inline');
        if (replaced && replaced.parentNode && window._originalLogoElement) {
          replaced.parentNode.replaceChild(window._originalLogoElement.cloneNode(true), replaced);
        } else if (window._originalLogoHTML && logoElement) {
          try { logoElement.innerHTML = window._originalLogoHTML; } catch (e) {}
        }
        window._replacedLogo = false;
        window._originalLogoElement = null;
        window._originalLogoHTML = null;
      }
      // after restoring DOM, refresh the reference to the current logo element
      logoElement = getLogoElement();

      // ensure all candidate logo <img> elements update to the proper theme logo
      try {
        const imgs = getAllLogoImageElements();
        imgs.forEach(img => {
          if (themeConfig && themeConfig.logo && theme !== 'cosmic') {
            img.src = themeConfig.logo;
          } else if (theme !== 'cosmic') {
            img.src = "data:image/svg+xml;utf8,<svg width='220' height='220' viewBox='0 0 220 220' xmlns='http://www.w3.org/2000/svg'><defs><radialGradient id='planetGradient' cx='50%' cy='35%' r='65%'><stop offset='0%' stop-color='%23a855f7'/><stop offset='45%' stop-color='%237f5af0'/><stop offset='100%' stop-color='%231e293b'/></radialGradient><linearGradient id='ringGradient' x1='0%' y1='50%' x2='100%' y2='50%'><stop offset='0%' stop-color='%2360a5fa'/><stop offset='50%' stop-color='%237f5af0'/><stop offset='100%' stop-color='%23f97316'/></linearGradient></defs><rect x='0' y='0' width='220' height='220' fill='none'/><ellipse cx='110' cy='96' rx='88' ry='32' fill='none' stroke='url(%23ringGradient)' stroke-width='8' opacity='0.9'/><circle cx='110' cy='96' r='60' fill='url(%23planetGradient)'/><circle cx='162' cy='70' r='10' fill='%23facc15'/><circle cx='56' cy='132' r='6' fill='%2360a5fa' opacity='0.9'/><text x='50%' y='180' text-anchor='middle' fill='%23e5e7eb' font-family='Cairo, -apple-system, BlinkMacSystemFont, system-ui, sans-serif' font-size='20' font-weight='700' letter-spacing='3'>STEP BY STEP</text></svg>";
          }
        });
      } catch (e) {}

      if (theme === 'cosmic' && isAuthPage()) {
        // inject galaxy image inline on auth pages only
        const galaxySrc = themeConfig.logo || '/assets/img/galaxy-logo.png';

        // save original IMG or HTML to restore later
        if (logoElement.tagName === 'IMG' && !window._originalLogoElement) {
          window._originalLogoElement = logoElement.cloneNode(true);
        } else if (!logoElement.tagName || logoElement.tagName !== 'IMG') {
          if (window._originalLogoHTML == null) try { window._originalLogoHTML = logoElement.innerHTML; } catch (e) { window._originalLogoHTML = null; }
        }

        // prepare an inline SVG fallback (galaxy icon)
        const galaxySVG = '<svg width="180" height="180" viewBox="0 0 180 180" xmlns="http://www.w3.org/2000/svg">'
          + '<defs><radialGradient id="g1" cx="30%" cy="30%" r="80%"><stop offset="0%" stop-color="#fff7d6"/><stop offset="40%" stop-color="#f6da09"/><stop offset="100%" stop-color="#7f5af0"/></radialGradient></defs>'
          + '<rect width="180" height="180" rx="12" fill="none"/>'
          + '<g transform="translate(90,90)">'
            + '<circle r="36" fill="url(#g1)" opacity="0.95"/>'
            + '<g opacity="0.55"><ellipse rx="70" ry="24" fill="none" stroke="#a78bfa" stroke-width="6" transform="rotate(18)"/></g>'
            + '<g opacity="0.35"><ellipse rx="90" ry="30" fill="none" stroke="#c084fc" stroke-width="4" transform="rotate(-12)"/></g>'
            + '<circle cx="48" cy="-28" r="3" fill="#fff"/><circle cx="-56" cy="14" r="2" fill="#fff"/><circle cx="20" cy="46" r="2" fill="#fff"/>'
          + '</g>'
        + '</svg>';

        if (logoElement.tagName === 'IMG') {
          const wrapper = document.createElement('div');
          wrapper.className = 'logo-image-inline';
          wrapper.style.display = 'inline-block';

          const img = document.createElement('img');
          img.width = 180; img.height = 180; img.alt = 'logo';
          img.style.display = 'block';
          img.src = galaxySrc;
          img.onerror = function () {
            try { wrapper.innerHTML = galaxySVG; } catch (e) { /* ignore */ }
          };

          wrapper.appendChild(img);
          // if image fails to load and onerror not fired (cached 404), ensure fallback after short timeout
          setTimeout(() => { if (!img.complete || img.naturalWidth === 0) wrapper.innerHTML = galaxySVG; }, 300);

          logoElement.parentNode.replaceChild(wrapper, logoElement);
          window._replacedLogo = true;
        } else {
          // non-img element: try to insert image then fallback to inline SVG
          const img = document.createElement('img');
          img.width = 180; img.height = 180; img.alt = 'logo'; img.style.display = 'block'; img.src = galaxySrc;
          img.onerror = function () { try { logoElement.innerHTML = galaxySVG; } catch (e) {} };
          // save original HTML already done above
          logoElement.innerHTML = '';
          logoElement.appendChild(img);
          setTimeout(() => { if (!img.complete || img.naturalWidth === 0) logoElement.innerHTML = galaxySVG; }, 300);
          window._replacedLogo = true;
        }
      } else {
        // non-cosmic or non-auth: if it's an IMG element, set the theme-specific src
        if (logoElement.tagName === 'IMG') {
          if (themeConfig.logo) {
            logoElement.src = themeConfig.logo;
          } else {
            logoElement.src = 'data:image/svg+xml;utf8,<svg width="180" height="180" xmlns="http://www.w3.org/2000/svg"><circle cx="90" cy="90" r="80" stroke="%23f6da09" stroke-width="8" opacity="0.6"/><circle cx="90" cy="90" r="50" stroke="%23f6da09" stroke-width="5" opacity="0.4"/><circle cx="90" cy="90" r="10" fill="%23f6da09"/></svg>';
          }
        }
      }
    }

    const themeToggle = getThemeToggle();
    if (themeToggle) {
      themeToggle.textContent = themeConfig.icon;
      themeToggle.setAttribute('aria-label', themeConfig.label);
    }
  }

  function toggleTheme() {
    const current = getTheme();
    let next = 'light';

    if (current === 'light') next = 'dark';
    else if (current === 'dark') next = 'cosmic';
    else if (current === 'cosmic') next = 'light';

    setTheme(next);
  }

  function clearCosmicBackground() {
    const existing = document.querySelector('.cosmic-bg-wrapper');
    if (existing) {
      try {
        if (existing._cosmicMutationObserver) existing._cosmicMutationObserver.disconnect();
      } catch (e) {}
      try {
        if (existing._cosmicUpdateHandler) window.removeEventListener('resize', existing._cosmicUpdateHandler);
      } catch (e) {}
      existing.remove();
    }
    const existingStyle = document.getElementById('cosmic-theme-styles');
    if (existingStyle) existingStyle.remove();
  }

  function generateCosmicBackground() {
    clearCosmicBackground();

    const docHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight, window.innerHeight);

    const wrapper = document.createElement('div');
    wrapper.className = 'cosmic-bg-wrapper';
    wrapper.style.position = 'absolute';
    wrapper.style.top = '0';
    wrapper.style.left = '0';
    wrapper.style.width = '100%';
    wrapper.style.height = docHeight + 'px';
    wrapper.style.overflow = 'hidden';
    wrapper.style.pointerEvents = 'none';
    wrapper.style.zIndex = '0';

    const css = `
      /* wrapper and basic elements */
      [data-theme=\"cosmic\"] .cosmic-bg-wrapper { position: absolute; top:0; left:0; width:100%; pointer-events:none; }
      [data-theme=\"cosmic\"] .cosmic-bg-wrapper .star { position:absolute; width:2px; height:2px; background:rgba(255,255,255,0.95); border-radius:50%; opacity:0.9; transform:translate(-50%,-50%); pointer-events:none; filter:drop-shadow(0 0 2px rgba(255,255,255,0.2)); }
      [data-theme=\"cosmic\"] .cosmic-bg-wrapper .planet { position:absolute; border-radius:50%; transform:translate(-50%,-50%); box-shadow:0 0 80px rgba(0,0,0,0.25); pointer-events:auto; }

      /* smooth falling stars */
      [data-theme=\"cosmic\"] .cosmic-bg-wrapper .falling-star { position:absolute; top:-8vh; width:3px; height:3px; border-radius:50%; background:rgba(255,255,255,0.9); filter:blur(1px); opacity:0.7; pointer-events:none; transform:translateY(0); }
      @keyframes cosmicFall { from { transform: translateY(0); opacity:0.8 } to { transform: translateY(120vh); opacity:0.15 } }

      /* ensure UI is above planets */
      [data-theme=\"cosmic\"] input, [data-theme=\"cosmic\"] textarea, [data-theme=\"cosmic\"] select, [data-theme=\"cosmic\"] button, [data-theme=\"cosmic\"] .card, [data-theme=\"cosmic\"] .modal { position:relative; z-index: 10; }

      /* form controls styling for cosmic theme only */
      [data-theme=\"cosmic\"] input, [data-theme=\"cosmic\"] textarea, [data-theme=\"cosmic\"] select { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); color: #e8e7ff; padding: .6rem .75rem; border-radius:6px; }
      [data-theme=\"cosmic\"] input::placeholder, [data-theme=\"cosmic\"] textarea::placeholder { color: rgba(230,230,240,0.6); }
      [data-theme=\"cosmic\"] .card, [data-theme=\"cosmic\"] .auth-card { background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); border: 1px solid rgba(255,255,255,0.04); box-shadow: 0 6px 24px rgba(3,6,23,0.6); color: #e8e7ff; }
      [data-theme=\"cosmic\"] button, [data-theme=\"cosmic\"] .btn { background: linear-gradient(90deg,#7f5af0,#a78bfa); color: #fff; border: none; }
      [data-theme=\"cosmic\"] button.secondary { background: transparent; border: 1px solid rgba(255,255,255,0.06); color: #e8e7ff; }

      /* subtle focus styles */
      [data-theme=\"cosmic\"] input:focus, [data-theme=\"cosmic\"] textarea:focus, [data-theme=\"cosmic\"] select:focus { outline: none; box-shadow: 0 0 0 4px rgba(127,90,240,0.12); border-color: rgba(127,90,240,0.6); }
    `;

    const styleEl = document.createElement('style');
    styleEl.id = 'cosmic-theme-styles';
    styleEl.appendChild(document.createTextNode(css));
    document.head.appendChild(styleEl);

    // stars
    for (let i = 0; i < 220; i++) {
      const star = document.createElement('div');
      star.className = 'star';
      star.style.top = `${Math.random() * 100}%`;
      star.style.left = `${Math.random() * 100}%`;
      star.style.opacity = (0.3 + Math.random() * 0.7).toFixed(2);
      star.style.transform = 'translate(-50%,-50%)';
      wrapper.appendChild(star);
    }

    // planets: generate random positions but avoid central band (30%-70%) to keep fields visible
    const planetColors = ['#7f5af0', '#a78bfa', '#c084fc', '#ffb86b', '#6ee7b7'];
    const planets = [];
    const generatePos = () => {
      let left, top, tries = 0;
      do {
        left = Math.random() * 100;
        top = Math.random() * 100;
        tries++;
      } while ((left > 30 && left < 70 && top > 30 && top < 70) && tries < 20);
      return { left: `${left}%`, top: `${top}%` };
    };

    const planetSizes = [160, 120, 100, 80, 60, 48];
    for (let i = 0; i < 6; i++) {
      const pos = generatePos();
      const size = planetSizes[i % planetSizes.length];
      const color = planetColors[i % planetColors.length];
      planets.push({ top: pos.top, left: pos.left, size: size + 'px', color });
    }

    planets.forEach((p, idx) => {
      const planet = document.createElement('div');
      planet.className = 'planet';
      planet.style.top = p.top;
      planet.style.left = p.left;
      planet.style.width = p.size;
      planet.style.height = p.size;
      planet.style.background = `radial-gradient(circle at 30% 30%, ${p.color}, #2b1b5a)`;
      planet.style.zIndex = '1';
      planet.style.opacity = (0.6 - idx * 0.05).toFixed(2);
      planet.style.pointerEvents = 'auto';

      planet.addEventListener('mouseenter', () => {
        planet.style.boxShadow = `0 0 ${Math.min(220, parseInt(p.size))}px rgba(0,0,0,0.3)`;
      });

      planet.addEventListener('mouseleave', () => {
        planet.style.boxShadow = '0 0 80px rgba(0,0,0,0.2)';
      });

      wrapper.appendChild(planet);
    });

    // soft falling stars (subtle, slow)
    for (let i = 0; i < 14; i++) {
      const fs = document.createElement('div');
      fs.className = 'falling-star';
      const left = Math.random() * 100;
      fs.style.left = `${left}%`;
      const size = (1 + Math.random() * 3).toFixed(2);
      fs.style.width = `${size}px`;
      fs.style.height = `${size}px`;
      fs.style.opacity = (0.4 + Math.random() * 0.6).toFixed(2);
      const dur = 12 + Math.random() * 14; // 12s - 26s
      const delay = Math.random() * dur;
      fs.style.animation = `cosmicFall ${dur.toFixed(1)}s linear ${-delay.toFixed(1)}s infinite`;
      wrapper.appendChild(fs);
    }

    document.body.appendChild(wrapper);

    function updateWrapperHeight() {
      const h = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight, window.innerHeight);
      wrapper.style.height = h + 'px';
    }

    window.addEventListener('resize', updateWrapperHeight);
    const mo = new MutationObserver(updateWrapperHeight);
    mo.observe(document.body, { childList: true, subtree: true });

    wrapper._cosmicMutationObserver = mo;
    wrapper._cosmicUpdateHandler = updateWrapperHeight;
  }

  function initTheme() {
    setTheme(getTheme());
  }

  function setupThemeToggle() {
    const themeToggle = getThemeToggle();
    if (themeToggle) {
      themeToggle.addEventListener('click', function (e) {
        e.preventDefault();
        toggleTheme();
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () {
      initTheme();
      setupThemeToggle();
    });
  } else {
    initTheme();
    setupThemeToggle();
  }

  window.toggleTheme = toggleTheme;
})();
