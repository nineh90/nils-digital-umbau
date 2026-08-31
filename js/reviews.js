document.addEventListener("DOMContentLoaded", () => {
  const textEl = document.getElementById("review-text");
  const authorEl = document.getElementById("review-author");
  const starsEl = document.getElementById("review-stars");
  const sourceEl = document.getElementById("review-source");

  const badgeStarsEl = document.getElementById("google-stars");
  const badgeRatingEl = document.getElementById("google-rating");
  const badgeEl = document.getElementById("google-badge");

  if (!textEl || !authorEl || !starsEl) return;

  fetch("assets/data/reviews.json")
    .then(res => res.json())
    .then(reviews => {
      if (!Array.isArray(reviews) || reviews.length === 0) return;

      /* --------------------------------
         ⭐ Durchschnitt berechnen
      -------------------------------- */
      const ratingCount = reviews.length;
      const ratingSum = reviews.reduce((sum, r) => sum + Number(r.rating || 0), 0);
      const averageRating = (ratingSum / ratingCount).toFixed(1);

      /* --------------------------------
         🟦 Google Badge befüllen
      -------------------------------- */
      if (badgeEl && badgeStarsEl && badgeRatingEl) {
        const rounded = Math.round(averageRating);
        badgeStarsEl.textContent = "★".repeat(rounded) + "☆".repeat(5 - rounded);
        badgeRatingEl.textContent = `${averageRating} aus ${ratingCount} Bewertungen.`;
      }

      const schema = {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "nils-digital",
        "url": "https://nils-digital.de/",
        "founder": {
            "@type": "Person",
            "name": "Nils Nehring"
        },
        "brand": {
            "@type": "Brand",
            "name": "nils-digital"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": averageRating,
            "bestRating": "5",
            "ratingCount": ratingCount
        }
        };


      const schemaScript = document.createElement("script");
      schemaScript.type = "application/ld+json";
      schemaScript.textContent = JSON.stringify(schema);
      document.head.appendChild(schemaScript);

      /* --------------------------------
         🔁 Rotierende Einzelbewertung
      -------------------------------- */
      const showRandomReview = () => {
        const review = reviews[Math.floor(Math.random() * reviews.length)];

        starsEl.innerHTML =
          "★".repeat(review.rating) + "☆".repeat(5 - review.rating);

        textEl.classList.remove("fade-in");
        authorEl.classList.remove("fade-in");
        if (sourceEl) sourceEl.classList.remove("fade-in");

        setTimeout(() => {
          textEl.textContent = `„${review.text}“`;
          authorEl.textContent = `– ${review.name}`;

          if (sourceEl && review.source) {
            sourceEl.textContent = `Quelle: ${review.source} Bewertung`;
            sourceEl.classList.add("fade-in");
          }

          textEl.classList.add("fade-in");
          authorEl.classList.add("fade-in");
        }, 200);
      };

      showRandomReview();
      setInterval(showRandomReview, 8000);
    })
    .catch(err =>
      console.error("Bewertungen konnten nicht geladen werden:", err)
    );
});
