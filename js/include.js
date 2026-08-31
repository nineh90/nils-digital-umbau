document.addEventListener("DOMContentLoaded", () => {
  // 🔍 Erkennen, ob wir uns in /pages/ befinden
  const inPages = window.location.pathname.includes("/pages/");
  const baseComponents = inPages ? "../components/" : "components/";

  // === Header laden (nur wenn Container vorhanden ist) ===
  const headerContainer = document.getElementById("header");
  if (headerContainer) {
    fetch(baseComponents + "header.html")
      .then((res) => res.text())
      .then((data) => {
        headerContainer.innerHTML = data;

        // === Pfade nach dem Include korrekt setzen ===
        const toHref = (path) => {
          if (!path) return "#";
          if (path === "index") return inPages ? "../index.html" : "index.html";
          return (inPages ? "../" : "") + path;
        };

        // Logo-Link
        const logo = document.querySelector(".logo");
        if (logo && logo.dataset.path) {
          logo.setAttribute("href", toHref(logo.dataset.path));
        }

        // Nav-Links
        document.querySelectorAll(".nav-link").forEach((a) => {
          const p = a.dataset.path;
          a.setAttribute("href", toHref(p));
        });

        // Header geladen → eigenes Event auslösen
        document.dispatchEvent(new Event("headerLoaded"));
      })
      .catch((err) => console.error("Header konnte nicht geladen werden:", err));
  } else {
    console.info("Kein Header-Container vorhanden – Header wird übersprungen.");
  }

// === Footer laden (immer, falls vorhanden) ===
const footerContainer = document.getElementById("footer");
if (footerContainer) {
  fetch(baseComponents + "footer.html")
    .then((res) => res.text())
    .then((data) => {
      footerContainer.innerHTML = data;

      // Footer ist jetzt im DOM → Cookie-Banner darf starten
      if (typeof initCookieBanner === "function") {
        initCookieBanner();
      }
    })
    .catch((err) => console.error("Footer konnte nicht geladen werden:", err));
} else {
  console.info("Kein Footer-Container vorhanden – Footer wird übersprungen.");
  }
}
);
