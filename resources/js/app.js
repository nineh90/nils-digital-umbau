/*
 * Einbettungen erst auf Klick laden.
 *
 * Google Forms und der Google-Kalender setzen bereits beim bloßen Aufruf der
 * Seite Cookies und übertragen die IP-Adresse. Solange die Besucherin nicht
 * ausdrücklich zustimmt, steht deshalb nur eine Vorschau da – der iframe
 * entsteht erst beim Klick.
 *
 * Ohne JavaScript bleibt die Vorschau samt Link zum Anbieter stehen: die Seite
 * ist damit weiterhin benutzbar, nur eben mit einem zusätzlichen Schritt.
 */
document.querySelectorAll('[data-einbettung]').forEach((behaelter) => {
    const knopf = behaelter.querySelector('[data-einbettung-laden]');

    knopf?.addEventListener('click', () => {
        const rahmen = document.createElement('iframe');
        rahmen.src = behaelter.dataset.einbettung;
        rahmen.title = behaelter.dataset.einbettungTitel || 'Eingebetteter Inhalt';
        rahmen.loading = 'lazy';
        rahmen.className = 'w-full rounded-xl border border-linie bg-white';
        rahmen.style.height = behaelter.dataset.einbettungHoehe || '1200px';

        behaelter.replaceChildren(rahmen);
        rahmen.focus();
    });
});
