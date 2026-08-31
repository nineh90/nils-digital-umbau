/* ================================================
   blog.js – Blog-Übersichtsseite
   Zuständig für: Rendering, Filter, Suche, Pagination
================================================ */

// -----------------------------------------------
//  HILFSFUNKTIONEN
// -----------------------------------------------

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: "smooth" });
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

function calcReadingTime(text) {
  if (!text) return null;
  const words = text.trim().split(/\s+/).length;
  const minutes = Math.ceil(words / 200); // ~200 Wörter/Min
  return minutes < 1 ? 1 : minutes;
}

// -----------------------------------------------
//  HAUPT-LOGIK
// -----------------------------------------------

document.addEventListener("DOMContentLoaded", () => {
  const container        = document.getElementById("blog-container");
  const searchInput      = document.getElementById("blog-search");
  const categoryContainer = document.getElementById("blog-categories");
  const pagination       = document.getElementById("pagination");

  const POSTS_PER_PAGE = 6;
  let currentPage    = 1;
  let filteredPosts  = [];
  let allPosts       = [];

  // -----------------------------------------------
  //  DATEN LADEN
  // -----------------------------------------------

  fetch("../assets/data/blog.json")
    .then(res => res.json())
    .then(posts => {
      allPosts = posts.sort((a, b) => new Date(b.date) - new Date(a.date));
      filteredPosts = allPosts;

      renderCategories();
      renderPosts();
      renderPagination();
      initSearch();
    })
    .catch(err => {
      console.error("Fehler beim Laden der Blogdaten:", err);
      if (container) container.innerHTML = "<p>Blogbeiträge konnten nicht geladen werden.</p>";
    });

  // -----------------------------------------------
  //  KATEGORIEN
  // -----------------------------------------------

  function renderCategories() {
    if (!categoryContainer) return;

    const categories = [...new Set(allPosts.map(p => p.category).filter(Boolean))];

    const allBtn = createFilterButton("Alle", true, () => {
      filteredPosts = allPosts;
      resetPage();
      setActiveButton(allBtn);
    });

    categoryContainer.appendChild(allBtn);

    categories.forEach(cat => {
      const btn = createFilterButton(cat, false, () => {
        filteredPosts = allPosts.filter(p => p.category === cat);
        resetPage();
        setActiveButton(btn);
      });
      categoryContainer.appendChild(btn);
    });
  }

  function createFilterButton(label, isActive, onClick) {
    const btn = document.createElement("button");
    btn.classList.add("filter-btn");
    if (isActive) btn.classList.add("active");
    btn.textContent = label;
    btn.addEventListener("click", onClick);
    return btn;
  }

  function setActiveButton(activeBtn) {
    categoryContainer.querySelectorAll(".filter-btn")
      .forEach(btn => btn.classList.remove("active"));
    activeBtn.classList.add("active");
  }

  // -----------------------------------------------
  //  SUCHE
  // -----------------------------------------------

  function initSearch() {
    if (!searchInput) return;

    searchInput.addEventListener("input", e => {
      const query = e.target.value.toLowerCase().trim();

      filteredPosts = query
        ? allPosts.filter(p =>
            (p.title + " " + p.teaser + " " + (p.content || ""))
              .toLowerCase()
              .includes(query)
          )
        : allPosts;

      resetPage();

      // Alle-Button wieder aktiv setzen
      const allBtn = categoryContainer?.querySelector(".filter-btn");
      if (allBtn) setActiveButton(allBtn);
    });
  }

  // -----------------------------------------------
  //  POSTS RENDERN
  // -----------------------------------------------

  function renderPosts() {
    if (!container) return;
    container.innerHTML = "";

    const start = (currentPage - 1) * POSTS_PER_PAGE;
    const pagePosts = filteredPosts.slice(start, start + POSTS_PER_PAGE);

    if (pagePosts.length === 0) {
      container.innerHTML = `<p class="blog-empty">Keine Beiträge gefunden.</p>`;
      return;
    }

    pagePosts.forEach((post, index) => {
      const card = buildCard(post, index);
      container.appendChild(card);
    });
  }

  function buildCard(post, index) {
    const card = document.createElement("article");
    card.classList.add("blog-card");
    // Stagger-Animation
    card.style.animationDelay = `${index * 0.07}s`;

    // Logos werden vollständig eingepasst (thumbFit: "contain"),
    // Fotos füllen die Kachel wie bisher aus (Standard: cover)
    const fitClass = post.thumbFit === "contain" ? " blog-thumb--contain" : "";

    const thumb = post.images?.length
      ? `<div class="blog-card-img-wrap">
           <img src="${post.images[0]}" class="blog-thumb${fitClass}" alt="${post.title}" loading="lazy" width="400" height="200">
         </div>`
      : `<div class="blog-card-img-wrap blog-card-img-placeholder"></div>`;

    const badge = post.category
      ? `<span class="category-badge cat-${normalizeCategory(post.category)}">${post.category}</span>`
      : "";

    const dateStr = new Date(post.date).toLocaleDateString("de-DE", {
      day: "2-digit", month: "short", year: "numeric"
    });

    const readingMinutes = calcReadingTime((post.content || "") + " " + (post.teaser || ""));
    const readingTime = readingMinutes
      ? `<span class="reading-time">⏱ ${readingMinutes} Min. Lesezeit</span>`
      : "";

    card.innerHTML = `
      <a href="blog-post.html?id=${post.id}" class="card-link-overlay" aria-label="${post.title} lesen"></a>
      ${thumb}
      <div class="blog-card-body">
        <div class="blog-card-meta">
          ${badge}
          <span class="blog-date">${dateStr}</span>
          ${readingTime}
        </div>
        <h3 class="blog-card-title">${post.title}</h3>
        <p class="blog-card-teaser">${post.teaser || ""}</p>
        <a href="blog-post.html?id=${post.id}" class="btn-sm blog-card-btn">Weiterlesen →</a>
      </div>
    `;

    return card;
  }

  // -----------------------------------------------
  //  PAGINATION
  // -----------------------------------------------

  function renderPagination() {
    if (!pagination) return;
    pagination.innerHTML = "";

    const totalPages = Math.ceil(filteredPosts.length / POSTS_PER_PAGE);
    if (totalPages <= 1) return;

    if (currentPage > 1) {
      pagination.appendChild(createPageBtn("«", () => changePage(currentPage - 1)));
    }

    for (let i = 1; i <= totalPages; i++) {
      const btn = createPageBtn(i, () => changePage(i));
      if (i === currentPage) btn.classList.add("active");
      pagination.appendChild(btn);
    }

    if (currentPage < totalPages) {
      pagination.appendChild(createPageBtn("»", () => changePage(currentPage + 1)));
    }
  }

  function createPageBtn(label, onClick) {
    const btn = document.createElement("button");
    btn.textContent = label;
    btn.classList.add("page-btn");
    btn.addEventListener("click", onClick);
    return btn;
  }

  function changePage(page) {
    currentPage = page;
    renderPosts();
    renderPagination();
    scrollToTop();
  }

  function resetPage() {
    currentPage = 1;
    renderPosts();
    renderPagination();
    scrollToTop();
  }
});