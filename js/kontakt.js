// kontakt.js

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contactForm");
  const successBox = document.getElementById("successBox");

  if (!form || !successBox) return;

  const submitBtn = form.querySelector("button[type='submit']");
  // ---------------------------------------------------------
  // Produktweiterleitung (?service=...)
  // ---------------------------------------------------------
  const params = new URLSearchParams(window.location.search);
  const service = params.get("service");

  if (service) {
    form.querySelector("input[name='subject']").value = service;
    form.querySelector("textarea[name='message']").value =
      `Ich interessiere mich für folgendes Angebot:\n${service}\n\nBitte sende mir weitere Informationen dazu.`;
  }

  // ---------------------------------------------------------
  // Live Validierung
  // ---------------------------------------------------------
  const inputs = form.querySelectorAll("input[name], textarea[name]");

  inputs.forEach(field => {
    field.addEventListener("input", () => {
      if (field.checkValidity()) {
        field.classList.add("valid");
      } else {
        field.classList.remove("valid");
      }
    });
  });

  // ---------------------------------------------------------
  // Absenden des Formulars
  // ---------------------------------------------------------
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    // DSGVO
    const privacy = document.getElementById("privacy");
    if (!privacy.checked) {
      alert("Bitte stimme der Datenschutzerklärung zu.");
      return;
    }

    // Honeypot
    const honey = document.getElementById("website");
    if (honey && honey.value !== "") {
      return;
    }

    // Email-Check
    const emailField = form.querySelector("input[name='email']");
    const emailValue = emailField.value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(emailValue)) {
      alert("Bitte gib eine gültige E-Mail-Adresse ein.");
      emailField.focus();
      emailField.classList.remove("valid");
      return;
    }

    // Spinner
    submitBtn.classList.add("btn-loading");

    const formData = new FormData(form);

    try {
      const response = await fetch("https://nils-digital.de/backend/contact.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json().catch(() => null);

      if (response.ok && result && result.success) {
        form.reset();
        form.querySelectorAll(".valid").forEach(el => el.classList.remove("valid"));

        submitBtn.classList.remove("btn-loading");

        successBox.style.display = "block";
        successBox.classList.add("fade-in");
        successBox.scrollIntoView({ behavior: "smooth" });

        // Box automatisch schließen
        setTimeout(() => {
          successBox.style.display = "none";
          successBox.classList.remove("fade-in");
        }, 8000);

      } else {
        alert(result?.message || "Fehler beim Senden.");
        submitBtn.classList.remove("btn-loading");
      }

    } catch (err) {
      console.error("Fetch-Error:", err);
      alert("Fehler beim Senden der Nachricht.");
      submitBtn.classList.remove("btn-loading");
    }
  });
});
