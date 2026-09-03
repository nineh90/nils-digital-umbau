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
 * Setzt zwei Werte als CSS-Eigenschaften; die Verschiebung selbst rechnet das
 * Stylesheet. So bleibt die Gestaltung in app.css und hier steht nur die Zahl.
 *
 * --scroll-y ist die reine Scroll-Position in Pixeln. Damit lässt sich nur
 * sehr zaghaft arbeiten: der Faktor muss zur längsten Seite passen, sonst
 * schiebt sich die Ebene auf halber Strecke aus dem Bild. Auf kurzen Seiten
 * bewegt sich dann sichtbar nichts mehr.
 *
 * --scroll-anteil ist stattdessen der zurückgelegte Anteil der Seite, 0 bis 1.
 * Der Weg lässt sich damit in vh angeben und ist am Ende jeder Seite genau
 * aufgebraucht – kurze wie lange. Erst dadurch kann die Bewegung groß genug
 * sein, dass man sie überhaupt bemerkt.
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
        const wurzel = document.documentElement;
        const strecke = wurzel.scrollHeight - window.innerHeight;

        wurzel.style.setProperty('--scroll-y', window.scrollY);
        wurzel.style.setProperty(
            '--scroll-anteil',
            strecke > 0 ? Math.min(window.scrollY / strecke, 1) : 0
        );

        angefordert = false;
    };

    window.addEventListener('scroll', () => {
        if (! angefordert) {
            angefordert = true;
            requestAnimationFrame(schreiben);
        }
    }, { passive: true });

    // Beim Drehen des Handys und beim Ziehen am Fenster ändert sich die
    // Strecke – der Anteil wäre sonst bis zum nächsten Scrollen falsch.
    window.addEventListener('resize', schreiben, { passive: true });

    schreiben();
}

/*
 * Auftritt beim Scrollen.
 *
 * Alles mit data-auftritt steigt beim Erscheinen sanft auf – und blendet
 * wieder aus, sobald es das Bild verlässt. Früher wurde das Element nach dem
 * ersten Auftritt abgemeldet: es gab also gar kein Ausblenden, und beim
 * Zurückscrollen stand alles hart da, während es beim Vorwärtsscrollen
 * hereinkam. Genau dieser Unterschied fällt auf.
 *
 * Der eingezogene Rand entscheidet über den Zeitpunkt. Unten 22%: die
 * Einblendung startet, wenn der Abschnitt ein Stück im Bild ist, nicht schon
 * wenn seine erste Pixelreihe die Kante streift. Oben nur 8% – zöge man dort
 * ebenso viel ein, verschwände ein Text, während man ihn oben am Rand noch
 * liest. Die beiden Werte dürfen deshalb ruhig verschieden sein.
 *
 * Kein threshold: ein Abschnitt, der höher ist als das Fenster, käme mit einer
 * Mindestfläche nie über die Schwelle und bliebe unsichtbar.
 */
const beobachter = new IntersectionObserver((eintraege) => {
    /*
     * Versetzter Einsatz nur, wenn wirklich mehrere Kacheln gemeinsam
     * hereinkommen – im mehrspaltigen Raster liest sich das als eine Bewegung
     * statt als Umschalten.
     *
     * Auf schmalen Geräten steht dasselbe Raster untereinander, dort kommt
     * jede Kachel für sich. Eine Verzögerung wäre dann keine Staffelung mehr,
     * sondern nur noch Trägheit: die Kachel steht schon im Bild und fängt erst
     * eine Viertelsekunde später an. Deshalb hängt der Verzug an der Zahl der
     * Meldungen und nicht an einem Umbruchpunkt – das trifft auch den Fall,
     * dass jemand das Fenster schmal zieht.
     */
    const staffeln = eintraege.filter((eintrag) => eintrag.isIntersecting).length > 1;
    let stufe = 0;

    eintraege.forEach((eintrag) => {
        if (eintrag.isIntersecting) {
            eintrag.target.style.setProperty(
                '--auftritt-verzug',
                `${staffeln ? Math.min(stufe++, 5) * 60 : 0}ms`
            );
            eintrag.target.classList.add('ist-da');
            return;
        }

        // Hinaus immer ohne Verzug: beim Verlassen wirkt eine Verzögerung wie
        // ein Hänger, nicht wie Absicht.
        eintrag.target.style.setProperty('--auftritt-verzug', '0ms');
        eintrag.target.classList.remove('ist-da');
    });
}, { rootMargin: '-8% 0px -22% 0px' });

/*
 * Richtung nach der Lage im Raster.
 *
 * Verglichen wird die Mitte der Kachel mit der Mitte ihres Rasters: was
 * deutlich links sitzt, kommt von links herein, was rechts sitzt, von rechts.
 * Der tote Bereich in der Mitte ist bewusst breit – eine mittlere Kachel in
 * einem Dreierraster soll von unten kommen und nicht zufällig zur einen oder
 * anderen Seite kippen.
 *
 * Bei einer Spalte liegen alle Kacheln auf der Mitte, damit fällt die
 * Seitwärtsbewegung von selbst weg. Genau deshalb wird gemessen und nicht
 * eine Medienabfrage geschrieben: die Raster wechseln bei verschiedenen
 * Breiten die Spaltenzahl, und beim Drehen des Handys ändert sie sich noch
 * einmal.
 */
const richtungSetzen = (el) => {
    if (el.dataset.aus === 'naeher') {
        return;
    }

    const raster = el.parentElement;

    if (! raster) {
        return;
    }

    const kachel = el.getBoundingClientRect();
    const umgebung = raster.getBoundingClientRect();

    if (kachel.width === 0 || umgebung.width - kachel.width < 40) {
        delete el.dataset.aus;

        return;
    }

    const versatz =
        (kachel.left + kachel.width / 2) - (umgebung.left + umgebung.width / 2);

    if (Math.abs(versatz) < umgebung.width * 0.12) {
        delete el.dataset.aus;

        return;
    }

    el.dataset.aus = versatz < 0 ? 'links' : 'rechts';
};

const auftrittsElemente = [...document.querySelectorAll('[data-auftritt]')];

auftrittsElemente.forEach((el) => {
    richtungSetzen(el);
    beobachter.observe(el);
});

// Beim Drehen des Handys und beim Ziehen am Fenster wechseln die Raster
// zwischen nebeneinander und gestapelt – dann stimmt die Richtung nicht mehr.
let umbruchZaehler;

window.addEventListener('resize', () => {
    clearTimeout(umbruchZaehler);
    umbruchZaehler = setTimeout(() => auftrittsElemente.forEach(richtungSetzen), 200);
}, { passive: true });

/*
 * Rückmeldung an das Inline-Skript im Layout: der Beobachter steht, das
 * Verstecken darf bestehen bleiben. Bleibt diese Zeile aus – weil das Modul
 * gar nicht ankam oder vorher etwas geworfen hat –, blendet das Layout nach
 * zwei Sekunden alles ein, statt die Seite leer zu lassen.
 */
window.auftrittBereit = true;

/*
 * Hinweise für Aktionen und Feiertage.
 *
 * Das Wegklicken selbst läuft ohne JavaScript – dafür sorgen die versteckte
 * Checkbox und :has() in app.css. Hier steht nur das Gedächtnis: wer den
 * Hinweis einmal geschlossen hat, soll ihn nicht bei jedem Seitenaufruf
 * wiedersehen.
 *
 * Deshalb startet der Hinweis im Markup mit hidden und wird erst sichtbar,
 * wenn feststeht, dass dieser Besucher ihn noch nicht kennt. Andersherum –
 * zeigen und nachträglich verstecken – blitzte er bei jedem Aufruf kurz auf.
 * Ohne JavaScript greift die Regel in app.css und zeigt ihn ohnehin.
 *
 * Der Schlüssel trägt die Uhrzeit der letzten Änderung: wird ein Hinweis
 * überarbeitet, ist er für den Besucher ein neuer.
 */
const hinweis = document.querySelector('[data-hinweis]');

if (hinweis) {
    const schluessel = hinweis.dataset.hinweis;
    const haeufigkeit = hinweis.dataset.haeufigkeit;

    // Ein privates Fenster oder abgeschaltete Website-Daten lassen den Zugriff
    // werfen. Dann gibt es eben kein Gedächtnis und der Hinweis erscheint.
    const speicher = (() => {
        try {
            return haeufigkeit === 'session' ? window.sessionStorage : window.localStorage;
        } catch {
            return null;
        }
    })();

    const schonGesehen = () => {
        if (haeufigkeit === 'always' || ! speicher) {
            return false;
        }

        try {
            return speicher.getItem(schluessel) === 'zu';
        } catch {
            return false;
        }
    };

    if (! schonGesehen()) {
        hinweis.hidden = false;

        const merken = () => {
            try {
                speicher?.setItem(schluessel, 'zu');
            } catch {
                // Kein Speicher, kein Gedächtnis – der Hinweis bleibt trotzdem zu.
            }
        };

        hinweis.querySelector('.hinweis__schalter')?.addEventListener('change', merken);

        // Escape schließt, wie man es von einem Fenster erwartet. Die Checkbox
        // ist der einzige Zustand, den es gibt – deshalb geht das darüber.
        document.addEventListener('keydown', (e) => {
            if (e.key !== 'Escape') {
                return;
            }

            const schalter = hinweis.querySelector('.hinweis__schalter');

            if (schalter && ! schalter.checked) {
                schalter.checked = true;
                merken();
            }
        });
    }
}
