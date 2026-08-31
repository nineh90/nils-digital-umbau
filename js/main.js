// --- Fade-In & Hero-Animation ---
document.addEventListener("DOMContentLoaded", () => {

  // Hero Title Fade-In
  const title = document.querySelector(".glow");
  if (title) {
    title.style.opacity = 0;
    setTimeout(() => {
      title.style.transition = "opacity 1.5s ease";
      title.style.opacity = 1;
    }, 300);
  }

  // Scroll Animations
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) entry.target.classList.add("visible");
    });
  });
  document.querySelectorAll(".card, .sunny, .hero-text").forEach((el) =>
    observer.observe(el)
  );

// -----------------------------------------------------
// 🌙 Seasonal Effects Script sicher und passiv nachladen
// -----------------------------------------------------
(function () {
  try {
    const inPages = window.location.pathname.includes("/pages/");
    const path = inPages ? "../js/season-effects.js" : "/js/season-effects.js";

    fetch(path, { method: "HEAD" })
      .then((res) => {
        if (!res.ok) return; // Datei existiert NICHT → Stop! Keine Fehler.
        
        const script = document.createElement("script");
        script.src = path;
        script.defer = true;
        document.body.appendChild(script);
      })
      .catch(() => {
        // Jegliche Fehler still ignorieren - beeinflusst nichts
      });

  } catch (e) {
    // Auch wenn es catastrophal schief läuft: NICHTS zerschießen 🙏
  }
})();



// -----------------------------------------------------
//  HOME PROJEKTE – Scroll-Dots (mobile)
// -----------------------------------------------------

const projTrack = document.getElementById("home-proj-track");
const projDots  = document.getElementById("home-proj-dots");

if (projTrack && projDots) {
  const cards = projTrack.querySelectorAll(".home-proj-card");

  // Dots erzeugen
  cards.forEach((_, i) => {
    const dot = document.createElement("span");
    dot.classList.add("home-proj-dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => {
      cards[i].scrollIntoView({ behavior: "smooth", inline: "start", block: "nearest" });
    });
    projDots.appendChild(dot);
  });

  // Aktiven Dot bei Scroll aktualisieren
  projTrack.addEventListener("scroll", () => {
    const scrollLeft = projTrack.scrollLeft;
    const cardWidth  = (cards[0]?.offsetWidth || 280) + 22; // gap ~22px
    const active     = Math.round(scrollLeft / cardWidth);

    projDots.querySelectorAll(".home-proj-dot").forEach((d, i) => {
      d.classList.toggle("active", i === active);
    });
  }, { passive: true });
}

});

// -----------------------------------------------------
//  HAMBURGER MENU
// -----------------------------------------------------

document.addEventListener("headerLoaded", () => {
  const menuToggle = document.getElementById("menu-toggle");
  const navMenu = document.getElementById("nav-menu");
  if (!menuToggle || !navMenu) return;

  // Burger Toggle
  menuToggle.addEventListener("click", () => {
    const isActive = menuToggle.classList.toggle("active");
    navMenu.classList.toggle("active");
    document.body.classList.toggle("no-scroll", isActive);
  });

  // Dropdown Toggle (Kontakt)
  navMenu.querySelectorAll(".dropdown-toggle").forEach(toggle => {
    toggle.addEventListener("click", (e) => {
      e.stopPropagation(); // verhindert Menü-Schließen
      toggle.parentElement.classList.toggle("active");
    });
  });

  // ✅ NUR echte Links schließen das Menü
  navMenu.querySelectorAll("a.nav-link").forEach((link) => {
    link.addEventListener("click", () => {
      menuToggle.classList.remove("active");
      navMenu.classList.remove("active");
      document.body.classList.remove("no-scroll");
    });
  });
});


function initCookieBanner() {
  const banner = document.getElementById("cookie-banner");

  if (!banner) {
    console.warn("⚠️ Cookie-Banner nicht gefunden – Header/Footer evtl. noch nicht geladen.");
    return;
  }

  const acceptBtn = document.getElementById("cookie-accept");
  const declineBtn = document.getElementById("cookie-decline");

  const consent = localStorage.getItem("cookieConsent");
  const consentTime = localStorage.getItem("cookieConsentTime");

  const now = Date.now();
  const threeMonths = 90 * 24 * 60 * 60 * 1000; // 90 Tage

  // ❗ Wenn es einen Consent gibt, aber er ist älter als 3 Monate → neu abfragen
  if (consent && consentTime && (now - parseInt(consentTime)) > threeMonths) {
    localStorage.removeItem("cookieConsent");
    localStorage.removeItem("cookieConsentTime");
  }

  // ❗ Nach 3-Monats-Prüfung erneut laden
  const freshConsent = localStorage.getItem("cookieConsent");

  if (!freshConsent) {
    banner.classList.remove("hidden");
  } else if (freshConsent === "accepted") {
    loadAnalytics();
  }

  // 🎯 Zustimmung
  acceptBtn.addEventListener("click", () => {
    localStorage.setItem("cookieConsent", "accepted");
    localStorage.setItem("cookieConsentTime", Date.now());
    banner.classList.add("hidden");
    loadAnalytics();
  });

  // ❌ Ablehnen
  declineBtn.addEventListener("click", () => {
    localStorage.setItem("cookieConsent", "declined");
    localStorage.setItem("cookieConsentTime", Date.now());
    banner.classList.add("hidden");
  });
}



// Cookie-Einstellungen im Footer
document.addEventListener("click", (e) => {
  if (e.target && e.target.id === "cookie-settings") {
    localStorage.removeItem("cookieConsent");
    const banner = document.getElementById("cookie-banner");
    if (banner) banner.classList.remove("hidden");
  }
});


function loadAnalytics() {
  // Verhindert doppeltes Laden
  if (window.analyticsLoaded) return;
  window.analyticsLoaded = true;

  // Google Tag Loader
  const script1 = document.createElement("script");
  script1.async = true;
  script1.src = "https://www.googletagmanager.com/gtag/js?id=G-Q78R7SM9W9";
  document.head.appendChild(script1);

  // GA4 Initialisierung
  const script2 = document.createElement("script");
  script2.innerHTML = `
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-Q78R7SM9W9');
  `;
  document.head.appendChild(script2);
}


console.log(
`%c
███╗   ██╗██╗██╗     ███████╗      ██████╗ ██╗ ██████╗ ██╗████████╗ █████╗ ██╗     
████╗  ██║██║██║     ██╔════╝      ██╔══██╗██║██╔════╝ ██║╚══██╔══╝██╔══██╗██║     
██╔██╗ ██║██║██║     ███████╗█████╗██║  ██║██║██║  ███╗██║   ██║   ███████║██║     
██║╚██╗██║██║██║     ╚════██║╚════╝██║  ██║██║██║   ██║██║   ██║   ██╔══██║██║     
██║ ╚████║██║███████╗███████║      ██████╔╝██║╚██████╔╝██║   ██║   ██║  ██║███████╗
╚═╝  ╚═══╝╚═╝╚══════╝╚══════╝      ╚═════╝ ╚═╝ ╚═════╝ ╚═╝   ╚═╝   ╚═╝  ╚═╝╚══════╝`,
"color:#5ad1c9; font-family:monospace;"
);

console.log(
  "%cNils-Digital",
  "color:#5ad1c9; font-size:16px; font-weight:bold;"
);


console.log(
  "%cKI-Automatisierung, Webentwicklung & Individuelle App.🐾",
  "color:#aaa; font-style:italic;"
);

console.log("%c👉 Kontakt:", "color:#5ad1c9; font-weight:bold;");
console.log("https://nils-digital.de/pages/kontakt.html");