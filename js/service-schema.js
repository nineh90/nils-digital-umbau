document.addEventListener("servicesLoaded", () => {

  fetch("../assets/data/services.json")
    .then(res => res.json())
    .then(data => {

      const allSchemas = [];

      data.forEach(category => {
        category.services.forEach(service => {

          const priceFinal = `${service.price}`;

          const schema = {
            "@context": "https://schema.org",
            "@type": "Service",
            "name": service.name,
            "serviceType": category.category,
            "description": service.description,
            "provider": {
              "@type": "Person",
              "name": "Nils Nehring"
            },
            "offers": {
              "@type": "Offer",
              "price": priceFinal,
              "priceCurrency": "EUR",
              "url": "https://nils-digital.de/pages/webdesign-leistung.html",
              "availability": "https://schema.org/InStock"
            }
          };

          allSchemas.push(schema);
        });
      });

      const script = document.createElement("script");
      script.type = "application/ld+json";
      script.textContent = JSON.stringify(allSchemas, null, 2);
      document.head.appendChild(script);
    });
});
