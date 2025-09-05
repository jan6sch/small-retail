// HAMBURGER-MENÜ
function toggleMenu(el) {
  const nav = document.getElementById('nav');
  nav.classList.toggle('open');
  el.classList.toggle('active');
}




// MARKEN SLIDER
let brands = [];
let brandIndex = 0;

function rotateBrands(dir) {
  if (brands.length === 0) return;
  brandIndex = (brandIndex + dir + brands.length) % brands.length;
  renderBrands();
}

function renderBrands() {
  const track = document.getElementById("brandTrack");
  track.innerHTML = "";

  const isMobile = window.innerWidth < 1024;
  const visible = isMobile ? 1 : 2;

  for (let i = 0; i < visible; i++) {
    const index = (brandIndex + i) % brands.length;
    const brand = brands[index];

    const div = document.createElement("div");
    div.className = "brand-item";
    div.innerHTML = `<img src="${brand.img}" alt="${brand.name}"><p>${brand.name}</p>`;
    track.appendChild(div);
  }
}

// Daten laden
fetch("data.php")
  .then(res => res.json())
  .then(data => {
    if (data.error) {
      document.getElementById("brand-error").textContent = data.error;
    } else {
      brands = data;
      renderBrands();
    }
  })
  .catch(() => {
    document.getElementById("brand-error").textContent = "Die Seite ist aktuell nicht erreichbar.";
  });

window.addEventListener("resize", renderBrands);


// ========== NAVIGATION: SMOOTH SCROLL & SCROLLSPY ==========
document.addEventListener("DOMContentLoaded", () => {
  const navLinks = document.querySelectorAll(".nav-links a");
  const sections = document.querySelectorAll("section[id]");
  const headerOffset = 100;

  // === Scrollspy Funktion ===
  function activateCurrentLink() {
    const scrollY = window.scrollY;

    sections.forEach(section => {
      const top = section.offsetTop - headerOffset - 10;
      const bottom = top + section.offsetHeight;
      const id = section.getAttribute("id");

      if (scrollY >= top && scrollY < bottom) {
        navLinks.forEach(link => {
          link.classList.remove("active");
          if (link.dataset.target === id) {
            link.classList.add("active");
          }
        });
      }
    });
  }

  // === Smooth Scroll bei Klick ===
  navLinks.forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const id = link.dataset.target;
      const section = document.getElementById(id);
      if (!section) return;

      // Scroll mit Animation
      window.scrollTo({
        top: section.offsetTop - headerOffset,
        behavior: "smooth"
      });

      // Menü (mobil) schließen
      if (window.innerWidth < 900) {
        document.getElementById("nav").classList.remove("open");
        document.querySelector(".hamburger").classList.remove("active");
      }

      // Kein Hash in URL
      history.replaceState(null, null, window.location.pathname);
    });
  });

  // === Scrollspy aktivieren ===
  window.addEventListener("scroll", activateCurrentLink);
  activateCurrentLink(); // beim Laden

  // === Falls Hash in URL beim Laden → zu richtiger Section scrollen + Link aktivieren ===
  if (window.location.hash) {
    const id = window.location.hash.substring(1);
    const section = document.getElementById(id);
    if (section) {
      setTimeout(() => {
        window.scrollTo({
          top: section.offsetTop - headerOffset,
          behavior: "auto"
        });
        navLinks.forEach(link => {
          link.classList.remove("active");
          if (link.dataset.target === id) {
            link.classList.add("active");
          }
        });
        history.replaceState(null, null, window.location.pathname);
      }, 0);
    }
  }
});





// Scroll + rote Markierung für marken.php
(function () {
  function getOffset() {
    // Versuche, fixe Header zu erkennen
    const candidates = ['.site-header', '.header', '.navbar', '.nav', '.topbar', '.main-header'];
    let offset = 0;
    for (const sel of candidates) {
      const el = document.querySelector(sel);
      if (!el) continue;
      const cs = getComputedStyle(el);
      if (cs.position === 'fixed') {
        offset += el.getBoundingClientRect().height;
      }
    }
    // CSS-Variable als Fallback/Override
    const varVal = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--sticky-offset')) || 0;
    return Math.max(offset, varVal, 96);
  }

  function highlight(el) {
    el.classList.remove('mkp-highlight');
    // Reflow erzwingen, damit die Animation erneut triggert
    void el.offsetWidth;
    el.classList.add('mkp-highlight');
    setTimeout(() => el.classList.remove('mkp-highlight'), 2000);
  }

  function scrollToEl(el, tries = 0) {
    const top = el.getBoundingClientRect().top + window.pageYOffset - getOffset();
    window.scrollTo({ top, behavior: 'smooth' });
    // Während Bilder laden, Layout kann springen -> kurz nachscrollen
    if (tries < 4) {
      setTimeout(() => scrollToEl(el, tries + 1), 200);
    }
  }

  function run() {
    if (!document.querySelector('.mkp-list')) return; // nur auf marken.php
    const params = new URLSearchParams(location.search);
    let id = params.get('marke');
    if (!id && location.hash.startsWith('#marke-')) id = location.hash.replace('#marke-', '');
    if (!id) return;

    const el = document.getElementById('marke-' + id);
    if (!el) return;

    highlight(el);
    scrollToEl(el);
  }

  // Warten bis alles (inkl. Bilder) geladen ist
  if (document.readyState === 'complete') run();
  else window.addEventListener('load', run);
})();

