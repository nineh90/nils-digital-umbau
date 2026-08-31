/* ================================================
   projects.js – Projekte-Seite
   Rendert Projektkarten aus projects.json
================================================ */

document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("project-container");
  if (!container) return;

  fetch("../assets/data/projects.json")
    .then(res => res.json())
    .then(projects => {
      container.innerHTML = "";

      projects.forEach((p, index) => {
        const card = document.createElement("article");
        card.classList.add("project-card");
        card.style.animationDelay = `${index * 0.1}s`;

        // Status-Label
        const statusLabels = {
          live:        { label: "Live",         cls: "status-live" },
          beta:        { label: "Beta",          cls: "status-beta" },
          development: { label: "In Entwicklung", cls: "status-dev" },
          planned:     { label: "Geplant",       cls: "status-planned" }
        };
        const status = statusLabels[p.status] || null;

        // Tags
        const tagsHtml = (p.tags || [])
          .map(t => `<span class="project-tag">${t}</span>`)
          .join("");

        // Bild – "imageFit": "cover" im JSON füllt die Kachel (für Screenshots),
        // ohne das Feld wird das Bild eingepasst (richtig für Logos).
        const fitCls = p.imageFit === "cover" ? " project-img--cover" : "";
        const imgHtml = p.image
          ? `<div class="project-img-wrap">
               <img src="${p.image}" alt="${p.title}" class="project-img${fitCls}" loading="lazy">
             </div>`
          : `<div class="project-img-wrap project-img-placeholder"></div>`;

        // Link
        const linkHtml = p.link
          ? p.internal
            ? `<a href="${p.link}" class="btn-sm project-btn">Projekt ansehen →</a>`
            : `<a href="${p.link}" class="btn-sm project-btn" target="_blank" rel="noopener noreferrer">Projekt ansehen →</a>`
          : "";

        card.innerHTML = `
          ${imgHtml}
          <div class="project-card-body">
            <div class="project-card-meta">
              <span class="project-type">${p.type}</span>
              ${status ? `<span class="project-status ${status.cls}">${status.label}</span>` : ""}
            </div>
            <h3 class="project-card-title">${p.title}</h3>
            <p class="project-card-desc">${p.description}</p>
            ${tagsHtml ? `<div class="project-tags">${tagsHtml}</div>` : ""}
            ${linkHtml}
          </div>
        `;

        container.appendChild(card);
      });
    })
    .catch(() => {
      container.innerHTML = "<p>Projekte konnten nicht geladen werden.</p>";
    });
});