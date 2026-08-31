document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("service-container");

  fetch("../assets/data/services.json")
    .then(res => res.json())
    .then(data => {

      let html = "";

      data.forEach(category => {
        html += `
          <h2>${category.category}</h2>
          <div class="service-category-grid">
        `;
        
    category.services.forEach(s => {
      let priceText = `${s.price}€`;

      if (s.unit === "eur-ab") {
        priceText = `ab ${s.price}€`;
      }

      if (s.unit === "eur-pro-monat") {
        priceText = `${s.price}€/Monat`;
      }

      if (s.unit === "eur-pro-stunde") {
        priceText = `${s.price}€/Std.`;
      }

      html += `
        <div class="service-card">
          <div class="service-icon">${s.icon}</div>
          <h3>${s.name}</h3>
          <p class="service-price">${priceText}</p>
          <p class="service-desc">${s.description}</p>
          <a href="kontakt.html?service=${encodeURIComponent(s.name)}"
            class="btn-sm service-btn">
            Angebot anfragen
          </a>
        </div>
      `;
    });

        html += `</div>`;
      });

      

      container.innerHTML = html;
      document.dispatchEvent(new Event("servicesLoaded"));
    })
    .catch(() => {
      container.innerHTML = "<p>Leistungen konnten nicht geladen werden.</p>";
    });
});
