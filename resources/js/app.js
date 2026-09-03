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

/*
 * Hintergrund-Parallaxe.
 *
 * Setzt die Scroll-Position als CSS-Eigenschaft --scroll-y; die Verschiebung
 * selbst rechnet das Stylesheet. So bleibt die Gestaltung in app.css und hier
 * steht nur die Zahl.
 *
 * requestAnimationFrame drosselt das Ganze auf einen Wert pro Bild: das
 * scroll-Ereignis feuert deutlich öfter, und jedes Schreiben in den Stil löst
 * ein Neuberechnen aus. Ohne die Bremse ruckelt genau das, was flüssig wirken
 * soll. { passive: true } sagt dem Browser zu, dass wir das Scrollen nicht
 * abfangen – er muss dann nicht auf uns warten.
 */
const bewegungAus = window.matchMedia('(prefers-reduced-motion: reduce)');

if (! bewegungAus.matches) {
    let angefordert = false;

    const schreiben = () => {
        document.documentElement.style.setProperty('--scroll-y', window.scrollY);
        angefordert = false;
    };

    window.addEventListener('scroll', () => {
        if (! angefordert) {
            angefordert = true;
            requestAnimationFrame(schreiben);
        }
    }, { passive: true });

    schreiben();
}

/*
 * Auftritt beim Scrollen.
 *
 * Alles mit data-auftritt steigt beim Erscheinen sanft auf. Ein
 * IntersectionObserver macht das ohne eigene Scroll-Rechnerei – der Browser
 * meldet sich, wenn ein Element in Sichtweite kommt.
 *
 * Nach dem ersten Auftritt wird das Element abgemeldet: es soll einmal
 * erscheinen, nicht bei jedem Vorbeiscrollen erneut auf- und abblenden.
 *
 * rootMargin zieht die untere Kante 10% nach oben, damit die Bewegung schon
 * anläuft, während das Element hereinkommt, statt erst wenn es steht.
 */
const beobachter = new IntersectionObserver(
    (eintraege, selbst) => {
        eintraege.forEach((eintrag) => {
            if (! eintrag.isIntersecting) {
                return;
            }

            /*
             * Versetzter Einsatz innerhalb einer Gruppe: die Kacheln eines
             * Rasters erscheinen nacheinander statt alle gleichzeitig. Das
             * liest sich als Bewegung, nicht als Umschalten. Bei mehr als
             * sechs Elementen wird nicht weiter gestaffelt – sonst wartet man
             * am Ende einer langen Liste spürbar.
             */
            const stufe = Number(eintrag.target.dataset.auftritt) || 0;
            eintrag.target.style.transitionDelay = `${Math.min(stufe, 6) * 70}ms`;
            eintrag.target.classList.add('ist-da');

            selbst.unobserve(eintrag.target);
        });
    },
    { rootMargin: '0px 0px -10% 0px', threshold: 0.05 }
);

document.querySelectorAll('[data-auftritt]').forEach((el) => beobachter.observe(el));
