# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Projekt

Offizielle Business-Website von **Nils-Digital** (Inhaber: Nils Nehring, info@nils-digital.de),
live unter https://nils-digital.de, gehostet bei Strato (Apache), Deployment per FTP/Filemanager.
Leistungen: KI-Automatisierung, Web-Entwicklung, App-Entwicklung, individuelle Lösungen.
Zielgruppe: KMU, Selbstständige, Freiberufler – Region Münster / Osnabrück / Ibbenbüren und deutschlandweit.

Positionierung: professionell aber persönlich, "du bekommst mich direkt, kein anonymes Team".
Alle Texte auf Deutsch, Du-Ansprache. Code-Kommentare ebenfalls auf Deutsch.

## Entwickeln & Testen

Kein Build-Step, kein Paketmanager, kein Git-Repo. Reines HTML/CSS/JS + ein PHP-Backend.

**Die Seite MUSS über HTTP ausgeliefert werden** – `file://` bricht, weil Header, Footer und
alle Inhalte per `fetch()` nachgeladen werden:

```bash
python3 -m http.server 8000      # → http://localhost:8000
php -S localhost:8000            # nötig, wenn backend/*.php mitgetestet werden soll
```

Warnung: `js/kontakt.js` postet an die **absolute Produktions-URL**
`https://nils-digital.de/backend/contact.php` – ein lokaler Formular-Test verschickt echte Mails.

Deployment = Dateien per FTP/Filemanager auf den Strato-Webspace kopieren. **Die `.htaccess` ist
eine versteckte Datei** – viele FTP-Clients zeigen sie standardmäßig nicht an ("versteckte Dateien
anzeigen" aktivieren), sonst wird sie beim Upload stillschweigend übersprungen.

## Architektur

### Seitengerüst
Jede Seite ist eine vollständige HTML-Datei (`index.html` im Root, alles andere in `pages/`).
Header und Footer werden **nicht** in den Dateien dupliziert, sondern zur Laufzeit injiziert:

- `js/include.js` erkennt an `location.pathname.includes("/pages/")`, ob relative Pfade ein `../`
  brauchen, lädt `components/header.html` + `components/footer.html` in `<div id="header">` /
  `<div id="footer">` und schreibt danach die `data-path`-Attribute der Nav-Links in echte `href`s um.
- Nav-Links im Header tragen deshalb **kein `href`**, sondern `data-path="pages/blog.html"` bzw.
  `data-path="index"`. Neue Menüpunkte immer nach diesem Muster ergänzen.
- Nach dem Einfügen des Headers feuert `document` das Custom-Event **`headerLoaded`** – alles, was
  Header-DOM braucht (Hamburger-Menü, Dropdown in `main.js`), muss darauf lauschen, nicht auf
  `DOMContentLoaded`.
- Der Footer-Callback ruft `initCookieBanner()` aus `main.js` auf, sobald er im DOM ist.

Jede Seite lädt am Ende des `<body>`: `include.js`, `main.js` und optional ihr Seiten-Skript
(`blog.js`, `post.js`, `projects.js`, `service-loader.js` + `service-schema.js`, `kontakt.js`).

### Inhalte kommen aus JSON, nicht aus HTML
`assets/data/` ist die Redaktionsschicht. Neue Inhalte werden dort gepflegt, nicht in HTML:

| Datei | Konsument | Inhalt |
|---|---|---|
| `blog.json` | `js/blog.js` (Übersicht), `js/post.js` (Einzelansicht) | Alle Blogbeiträge inkl. Shop-Produktposts |
| `projects.json` | `js/projects.js` | Karten auf `pages/projekte.html` |
| `services.json` | `js/service-loader.js`, `js/service-schema.js` | Leistungen + Preise auf `pages/webdesign-leistung.html` |
| `reviews.json` | `js/reviews.js` | Kundenstimmen-Rotation auf der Startseite |
| `seasonal.json` | `js/season-effects.js` | Datumsgesteuerte Popups/Effekte (Advent, Halloween, …) |

**Wichtig – Pfad-Konvention:** Bildpfade in `blog.json`, `projects.json` stehen als
`../assets/images/...`, weil die konsumierenden Seiten in `pages/` liegen. `post.js` und
`service-schema.js` normalisieren das für Schema.org per `.replace("../", "")`.
`seasonal.json` wird dagegen absolut (`/assets/data/seasonal.json`) geladen.

**Achtung – Duplikat:** Die Projekt-Karten im Carousel auf `index.html` sind **hart im HTML**
(`.home-proj-card`) und werden *nicht* aus `projects.json` gerendert. Ein neues Projekt muss an
beiden Stellen gepflegt werden, sonst laufen Startseite und Projektseite auseinander.

### Blogbeiträge anlegen
Objekt an `assets/data/blog.json` anhängen:

```json
{
  "id": 45,
  "category": "Projekte",
  "title": "Titel",
  "date": "2026-07-30",
  "teaser": "1–2 Sätze, wird als Meta-Description und og:description genutzt",
  "content": "Fließtext im Mini-Markdown",
  "images": ["../assets/images/blog/bild.jpg"],
  "thumbFit": "contain",
  "links": [{ "url": "https://…", "text": "→ Linktext" }]
}
```

- `id` ist die URL: `pages/blog-post.html?id=45`. Muss eindeutig sein. Aktuell 43 Posts,
  höchste ID 44, ID 42 fehlt (Lücke ist unkritisch, aber nicht neu vergeben).
- Sortierung erfolgt automatisch nach `date` absteigend; Übersicht paginiert mit 6 Posts/Seite.
- Lesezeit wird aus `content` + `teaser` berechnet (200 Wörter/Min), nicht im JSON pflegen.
- `images[0]` wird zum Hero-Bild des Posts und zum Thumbnail der Karte; ab `images[1]` erscheinen
  die Bilder im Fließtext.
- `links` erzeugt Buttons am Ende des Beitrags – **Links gehören nicht in `content`**, der
  Markdown-Parser kennt keine Link-Syntax.
- `thumbFit` (optional) steuert nur die Kachel in der Blog-Übersicht: `"contain"` passt das Bild
  vollständig ein (richtig für **Logos**), ohne das Feld füllt es die Kachel und wird beschnitten
  (richtig für **Fotos und Screenshots**). Gesetzt in `js/blog.js` → `.blog-thumb--contain`.
- `product` (optional, für Shop-Posts) rendert Produktbox + Product-JSON-LD und unterdrückt das
  Hero-Bild und die `links`-Buttons.

Der Mini-Markdown-Parser in `js/post.js` (`parseContent`) kann ausschließlich:
`## H2`, `### H3`, `**fett**`, `*kursiv*`, `- Listenpunkt`, Leerzeile = neuer Absatz,
einzelner Zeilenumbruch = `<br>`. Alles andere landet als Rohtext auf der Seite.
Inhalte werden per `innerHTML` eingesetzt – keine fremden/ungeprüften Texte einpflegen.

**Kategorien:** Die Badge-Farbe entsteht aus `normalizeCategory()` (lowercase, Leerzeichen → `-`,
Umlaute ausgeschrieben) und einer Klasse `.cat-…` in `css/main.css` (~Zeile 1534). Achtung: Aus
`" - "` werden dabei **drei** Bindestriche, z. B. `Lernsoftware - Lerndex` →
`.cat-lernsoftware---lerndex`. Aktuell haben alle verwendeten Kategorien eine eigene Farbe.
Produktreihen bekommen eine eigene Serien-Kategorie (`Lernsoftware - Lerndex`,
`Pflegesoftware - Pflegedex`), damit der Filter auf `pages/blog.html` nutzbar bleibt.
Bei einer neuen Kategorie also immer eine `.cat-…`-Regel ergänzen, sonst fällt das Badge auf
transparentes Schwarz zurück.

### SEO
- Statische Seiten tragen ihren kompletten SEO-Block inline im `<head>`: Meta-Description,
  Keywords, Canonical, Open Graph, Twitter Card und ein oder mehrere JSON-LD-Blöcke
  (`ProfessionalService` auf `index.html`, `Blog`, `CollectionPage`, `BreadcrumbList`, …).
  Neue Seiten nach diesem Muster aufbauen – am besten aus einer bestehenden Seite kopieren.
- `pages/blog-post.html` ist die Ausnahme: leere Meta-Tags mit IDs (`seo-description`,
  `og-title`, `jsonld`, …), die `post.js` → `setSeoDynamic()` zur Laufzeit füllt.
  Das ist clientseitig – neue Beiträge zusätzlich sinnvoll intern verlinken.
- `sitemap.xml` wird **manuell** gepflegt (Blog-Posts sind dort nicht einzeln gelistet).
- `robots.txt` sperrt bewusst `pages/sunnycam.html` und `pages/shop.html`.

### CSS
`css/main.css` (~2.500 Zeilen) ist das Hauptstylesheet für alle Seiten, thematisch in
Kommentarblöcke gegliedert. Design-Tokens stehen als Custom Properties im `:root` ganz oben
(Dark Theme: `--bg-main:#0d1117`, `--accent:#00bcd4`, `--text-main`, `--bg-card` …) – Farben immer
über diese Variablen, nie hart kodieren. Fonts: `Fredoka` (Headlines) + `Roboto Mono` (Body).
Ergänzend gibt es nur zwei seiten-spezifische Stylesheets: `css/team.css`, `css/ueber-uns.css`.
Mobile First: einige Regeln gelten gezielt nur für Unterseiten (`body:not(.home)`), die Startseite
trägt `class="home"`.

### Backend (PHP)
Trotz "HTML/CSS/JS-Projekt" existiert ein PHP-Teil:

- `backend/contact.php` – nimmt das Kontaktformular per POST an, sanitized, prüft die Honeypot-
  Feldnamen `website`, versendet zwei HTML-Mails (Admin-Benachrichtigung + Auto-Antwort an den
  Kunden) über PHPMailer/SMTP Strato (`smtp.strato.de:465`), antwortet JSON `{success: bool}`.
- `backend/config.php` enthält die echten SMTP-Zugangsdaten, `config.example.php` ist die Vorlage.
  `config.php` niemals in Ausgaben, Commits oder Uploads streuen.
- `backend/phpmailer/` ist eine eingecheckte PHPMailer-Kopie (kein Composer).

Projektanfrage (`pages/projektfragebogen.html`) und Terminbuchung (`pages/reservierung.html`)
laufen nicht über dieses Backend, sondern über eingebettete iframes (Google Forms bzw. Google
Calendar Appointment Scheduling). Der Shop (`pages/shop.html`) ist ein Spreadshop-Embed.

### demo/
`demo/brackemeyer_alltagsbegleitung/` ist eine eigenständige Kunden-Demo-Website in PHP mit
`includes/header.php` + `includes/footer.php`. Sie gehört nicht zur Hauptseite, zeigt aber genau
das Include-Muster, das für Phase 4 der Roadmap (Migration der Hauptseite auf PHP) angestrebt wird.

### Server-Konfiguration
`.htaccess` im Root regelt für Apache/Strato: HTTPS-Zwang und `www` → ohne `www` (kanonisch ist
`https://nils-digital.de`), Verzeichnis-Listing aus, Sperre für `backend/config.php` und Dotfiles,
gzip-Komprimierung, Browser-Caching (Bilder lang, CSS/JS mittel, HTML/JSON kurz) und
Security-Header. Bewusst **ohne** Content-Security-Policy – die Seite bindet Google Fonts,
Analytics, Spreadshop, TikTok-Embeds und Google-Forms-iframes ein, eine CSP müsste gezielt dafür
aufgebaut werden. Es gibt noch **keine 404-Seite**, entsprechend auch keine `ErrorDocument`-Zeile.

### Tracking / DSGVO
Google Analytics (`G-Q78R7SM9W9`) wird **erst nach Zustimmung** im Cookie-Banner nachgeladen
(`loadAnalytics()` in `main.js`). Consent liegt in `localStorage` (`cookieConsent`,
`cookieConsentTime`) und läuft nach 90 Tagen ab. Keine Tracking-Skripte fest ins HTML einbauen.

## Roadmap

- Phase 1: Inhalt und Design auf Business umbauen (HTML/CSS/JS) – weitgehend erledigt
- Phase 2: Code aufräumen – veraltete Schnipsel, auskommentierte Blöcke, unbenutzte Dateien
- Phase 3: SEO ausbauen
- Phase 4 (später): Migration auf PHP mit Includes für saubere Wartbarkeit

## Arbeitsweise in diesem Repo

- Bestehende Dateien zuerst analysieren, dann Bericht geben.
- Nichts löschen oder umbauen ohne vorherige Absprache; vor jeder Roadmap-Phase Freigabe abwarten.
- Kein Git – Änderungen sind sofort "echt", es gibt kein Undo über Versionskontrolle.
- Da die Live-Seite parallel online ist: Inhalte (JSON) und Struktur (HTML/CSS/JS) getrennt denken;
  reine Inhaltsupdates betreffen nur `assets/data/` + `assets/images/`.
