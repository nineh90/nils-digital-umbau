/* ================================================
   post.js – Einzelner Blog-Post
   Zuständig für: Post laden, SEO setzen, Author-Box
================================================ */

document.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const id     = params.get("id");

  if (!id) return;

  fetch("../assets/data/blog.json")
    .then(res => res.json())
    .then(posts => {
      const post = posts.find(p => p.id == id);
      if (!post) {
        document.getElementById("post-title").textContent = "Beitrag nicht gefunden.";
        return;
      }

      renderPost(post);
      setSeoDynamic(post);
      renderAuthorBox();
    })
    .catch(err => console.error("Fehler beim Laden des Posts:", err));
});

// -----------------------------------------------
//  POST RENDERN
// -----------------------------------------------

function renderPost(post) {

  // ---- Lesezeit berechnen ----
  const wordCount    = ((post.content || "") + " " + (post.teaser || "")).trim().split(/\s+/).length;
  const readMinutes  = Math.max(1, Math.ceil(wordCount / 200));

  // ---- Überschrift & Meta ----
  document.getElementById("post-title").textContent = post.title;

  const dateEl = document.getElementById("post-date");
  if (dateEl) {
    dateEl.innerHTML = `
      <span>${new Date(post.date).toLocaleDateString("de-DE", { day: "2-digit", month: "long", year: "numeric" })}</span>
      <span class="reading-time-post">⏱ ${readMinutes} Min. Lesezeit</span>
    `;
  }

  // ---- Kategorie-Badge ----
  const catBadge = document.getElementById("post-category");
  if (catBadge) {
    if (post.category) {
      catBadge.textContent = post.category;
      catBadge.classList.add(`cat-${normalizeCategory(post.category)}`);
    } else {
      catBadge.style.display = "none";
    }
  }

  // ---- Produkt-Badge ----
  if (post.product) {
    const badge = document.getElementById("product-badge");
    if (badge) {
      const icons = { hoodie: "🧥", tasse: "☕", shirt: "👕", sticker: "🟦", becher: "🥤", design: "🎨", produkt: "⭐" };
      const type  = post.product.type || "produkt";
      badge.textContent = `${icons[type] || "⭐"} ${type.charAt(0).toUpperCase() + type.slice(1)}`;
      badge.classList.remove("hidden");
    }
  }

  // ---- Produktbox ----
  if (post.product) {
    const productBox = document.getElementById("product-box");
    if (productBox) {
      const priceHtml = post.product.price
        ? `<p class="product-price pretty-price">${post.product.price} ${post.product.currency}</p>`
        : "";

      productBox.innerHTML = `
        <div class="product">
          <img src="${post.product.image}" alt="${post.product.name}" class="product-img" loading="lazy">
          <h3>${post.product.name}</h3>
          ${priceHtml}
          <a href="${post.product.shopUrl}" target="_blank" rel="noopener noreferrer" class="btn">Zum Shop</a>
        </div>
      `;

      appendProductSchema(post);
    }
  }

  // ---- Hero-Bild (erstes Bild → Header der Karte) ----
  if (!post.product && post.images?.length) {
    const heroWrap = document.getElementById("post-hero-wrap");
    const heroImg  = document.getElementById("post-hero-img");
    if (heroWrap && heroImg) {
      heroImg.src = post.images[0];
      heroImg.alt = post.title;
      heroWrap.style.display = "block";
    }
  }

  // ---- Post-Body ----
  const body = document.getElementById("post-body");
  if (!body) return;

  let html = "";

  // Weitere Bilder (ab Index 1) bleiben im Content
  if (!post.product && post.images?.length > 1) {
    html += `<div class="blog-images">`;
    post.images.slice(1).forEach(img => {
      html += `<img src="${img}" class="blog-image" alt="${post.title}" loading="lazy">`;
    });
    html += `</div>`;
  }

  html += parseContent(post.content || "");

  if (post.product?.image) {
    html += `
      <div class="blog-images">
        <img src="${post.product.image}" class="blog-image" alt="${post.title}" loading="lazy">
      </div>
    `;
  }

  if (!post.product && post.links?.length) {
    html += `<div class="blog-links">`;
    post.links.forEach(l => {
      html += `<a href="${l.url}" class="btn-sm" target="_blank" rel="noopener noreferrer">${l.text}</a>`;
    });
    html += `</div>`;
  }

  body.innerHTML = html;
}

// -----------------------------------------------
//  AUTHOR-BOX
// -----------------------------------------------

function renderAuthorBox() {
  const body = document.getElementById("post-body");
  if (!body) return;

  const authorBox = document.createElement("div");
  authorBox.classList.add("author-box");
  authorBox.innerHTML = `
    <img
      src="../assets/images/sunny-nils.jpg"
      alt="Nils Nehring"
      class="author-avatar"
      width="64"
      height="64"
      loading="lazy"
    >
    <div class="author-info">
      <span class="author-name">Nils Nehring</span>
      <span class="author-role">Gründer & Entwickler · Nils-Digital</span>
      <p class="author-bio">
        Entwickler, Möglichmacher & Hundemensch 🐾. Ich baue Webseiten mit Herz –
        und schreibe hier über Technik, Projekte und das Leben mit Sunny.
      </p>
    </div>
  `;

  // Author-Box nach dem Post-Body einfügen
  body.after(authorBox);
}

// -----------------------------------------------
//  SEO — DYNAMISCH SETZEN
// -----------------------------------------------

function setSeoDynamic(post) {
  const pageUrl = `https://nils-digital.de/pages/blog-post.html?id=${post.id}`;
  const teaser  = post.teaser || (post.content || "").slice(0, 160);

  let ogImage = post.product?.image || post.images?.[0] || "/assets/images/sunny-nils.jpg";
  ogImage = window.location.origin + "/" + ogImage.replace("../", "");

  document.title = `${post.title} – nils-digital`;

  setMeta("seo-description", "content", teaser);
  setMeta("seo-canonical",   "href",    pageUrl);
  setMeta("og-title",        "content", post.title);
  setMeta("og-description",  "content", teaser);
  setMeta("og-image",        "content", ogImage);
  setMeta("og-url",          "content", pageUrl);
  setMeta("tw-title",        "content", post.title);
  setMeta("tw-description",  "content", teaser);
  setMeta("tw-image",        "content", ogImage);

  // JSON-LD BlogPosting
  const jsonLd = {
    "@context": "https://schema.org",
    "@type":    "BlogPosting",
    "headline": post.title,
    "description": teaser,
    "image":    ogImage,
    "datePublished": post.date,
    "url":      pageUrl,
    "author": {
      "@type": "Person",
      "name":  "Nils Nehring",
      "url":   "https://nils-digital.de/pages/ueber-mich.html"
    },
    "publisher": {
      "@type": "Organization",
      "name":  "nils-digital",
      "url":   "https://nils-digital.de"
    }
  };

  const jsonldEl = document.getElementById("jsonld");
  if (jsonldEl) jsonldEl.textContent = JSON.stringify(jsonLd);
}

// -----------------------------------------------
//  PRODUKT SCHEMA
// -----------------------------------------------

function appendProductSchema(post) {
  const schema = {
    "@context":   "https://schema.org/",
    "@type":      "Product",
    "name":       post.product.name,
    "image":      window.location.origin + "/" + post.product.image.replace("../", ""),
    "description": post.teaser,
    "brand":      { "@type": "Brand", "name": "SunnyCam" },
    "offers": {
      "@type":        "Offer",
      "price":        post.product.price || "",
      "priceCurrency": post.product.currency || "EUR",
      "availability": "https://schema.org/" + post.product.availability,
      "url":          post.product.shopUrl
    }
  };

  const script = document.createElement("script");
  script.type = "application/ld+json";
  script.textContent = JSON.stringify(schema);
  document.head.appendChild(script);
}

// -----------------------------------------------
//  UTILS
// -----------------------------------------------

function setMeta(id, attr, value) {
  const el = document.getElementById(id);
  if (el) el.setAttribute(attr, value);
}

function normalizeCategory(cat) {
  return cat
    .toLowerCase()
    .replace(/ /g, "-")
    .replace(/ü/g, "ue")
    .replace(/ö/g, "oe")
    .replace(/ä/g, "ae")
    .replace(/ß/g, "ss");
}

// -----------------------------------------------
//  MINI-MARKDOWN PARSER
//  Unterstützt:
//    ## Überschrift    → <h2>
//    ### Überschrift   → <h3>
//    **fett**          → <strong>
//    *kursiv*          → <em>
//    - Listenpunkt     → <ul><li>
//    Leerzeile         → neuer <p>-Absatz
// -----------------------------------------------

function parseContent(raw) {
  // Zeilenenden normalisieren
  const text = raw.replace(/\r\n/g, "\n").replace(/\r/g, "\n");

  // In Blöcke aufteilen (doppelte Leerzeile = Trenner)
  const blocks = text.split(/\n{2,}/);

  return blocks.map(block => {
    const trimmed = block.trim();
    if (!trimmed) return "";

    // Inline-Formatierung anwenden (gilt für alle Block-Typen)
    const inline = applyInline(trimmed);

    // --- Überschriften ---
    if (inline.startsWith("### ")) {
      return `<h3 class="post-heading">${inline.slice(4)}</h3>`;
    }
    if (inline.startsWith("## ")) {
      return `<h2 class="post-heading">${inline.slice(3)}</h2>`;
    }

    // --- Listen ---
    // Alle Zeilen die mit "- " beginnen → <ul>
    const lines = trimmed.split("\n");
    const isAllListItems = lines.every(l => l.trim().startsWith("- "));

    if (isAllListItems) {
      const items = lines
        .map(l => `<li>${applyInline(l.trim().slice(2))}</li>`)
        .join("");
      return `<ul class="post-list">${items}</ul>`;
    }

    // --- Gemischter Block: Text mit einzelnen Listenpunkten ---
    // (z.B. ein Satz, dann eine Liste) → zeilenweise verarbeiten
    const hasSomeListItems = lines.some(l => l.trim().startsWith("- "));

    if (hasSomeListItems) {
      let html = "";
      let inList = false;

      lines.forEach(line => {
        const t = line.trim();
        if (t.startsWith("- ")) {
          if (!inList) { html += `<ul class="post-list">`; inList = true; }
          html += `<li>${applyInline(t.slice(2))}</li>`;
        } else {
          if (inList) { html += `</ul>`; inList = false; }
          if (t) html += `<p class="post-paragraph">${applyInline(t)}</p>`;
        }
      });

      if (inList) html += `</ul>`;
      return html;
    }

    // --- Normaler Absatz ---
    // Einzelne Zeilenumbrüche innerhalb eines Blocks → <br>
    const withBreaks = inline.replace(/\n/g, "<br>");
    return `<p class="post-paragraph">${withBreaks}</p>`;

  }).join("\n");
}

// Inline-Formatierung: **fett**, *kursiv*
function applyInline(text) {
  return text
    // **fett**
    .replace(/\*\*(.+?)\*\*/g, "<strong>$1</strong>")
    // *kursiv* (nicht verwechseln mit **)
    .replace(/(?<!\*)\*(?!\*)(.+?)(?<!\*)\*(?!\*)/g, "<em>$1</em>");
}