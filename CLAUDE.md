# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Projekt

Offizielle Business-Website von **Nils-Digital** (Inhaber: Nils Nehring, info@nils-digital.de).
Leistungen: KI-Automatisierung, Web-Entwicklung, App-Entwicklung, individuelle Lösungen.
Zielgruppe: KMU, Selbstständige, Freiberufler – Region Münster / Osnabrück / Ibbenbüren und
deutschlandweit.

Positionierung: professionell aber persönlich, "du bekommst mich direkt, kein anonymes Team".
**Reiner Firmenauftritt** – über andere zu berichten (Kunden, Kooperationen, Projekte) gehört
dazu, Privates über Nils selbst nicht. Danach ist der Blog im September 2026 ausgedünnt worden.
Alle Texte auf Deutsch, Du-/Ihr-Ansprache. **Code, Kommentare, Klassennamen, Routen und
Feldnamen ebenfalls auf Deutsch**, soweit es nicht Laravel-Konventionen bricht: Blade-Views und
Komponenten heißen `kopfzeile`, `beitragskachel`, `seiten/leistungen`; Eloquent-Modelle und
Migrationen bleiben englisch (`Post`, `Review`, `posts.published_at`), weil Laravel daraus
Tabellennamen und Beziehungen ableitet.

### Zwei Adressen, ein Stand

| Adresse | Was dort läuft |
|---|---|
| `https://nils-digital.de` | **noch die alte statische Seite** bei Strato, unverändert |
| `https://neu.nils-digital.de` | diese Laravel-Anwendung auf dem Hostinger-VPS, `noindex` |
| `http://localhost:8000` | lokale Entwicklung – hier wird gearbeitet |

Die Vorschau dient Nils ausschließlich dazu, das Layout auf echten Geräten zu prüfen. **Inhalte
werden dort nicht gepflegt** und fließen nicht zurück; die lokale SQLite-Datei ist beim Bauen die
Wahrheit. Der Domain-Umzug steht noch aus – bis dahin ist die alte Seite live.

## Der Umbau (Stand: September 2026)

Das Projekt war bis August 2026 eine statische HTML/CSS/JS-Seite mit einem kleinen PHP-Backend,
per FTP auf einen Strato-Webspace kopiert. Inhalte lagen als JSON in `assets/data/`, Kopf- und
Fußzeile wurden zur Laufzeit per `fetch()` nachgeladen, SEO-Tags für Blogbeiträge erst im Browser
gesetzt.

Seit dem Umbau ist es eine **Laravel-13-Anwendung mit Filament als Redaktion**. Die alte Seite
liegt vollständig unter `legacy/` und ist nur noch Quelle für den Import – **kein Vorbild mehr**.
Wer dort etwas nachschlägt, sollte wissen, dass die Lösung im neuen Stand meist bewusst anders ist:

| Früher | Heute |
|---|---|
| `js/include.js` lud Header/Footer per `fetch()`, Nav-Links trugen `data-path` statt `href` | Blade-Layout `components/layouts/oeffentlich.blade.php`, echte `href`s, funktioniert ohne JS |
| Inhalte in `assets/data/*.json`, von Hand gepflegt | Datenbank, gepflegt über Filament auf `/admin` |
| Meta-Tags für Beiträge per `post.js` im Browser gesetzt | `<x-seo>` rendert alles serverseitig ins HTML |
| Projekt-Karten doppelt (hart in `index.html` **und** in `projects.json`) | eine Quelle: Tabelle `projects` |
| `sitemap.xml` von Hand gepflegt | `SitemapController` erzeugt sie aus der Datenbank |
| Mini-Markdown-Parser in `js/post.js` | CommonMark serverseitig, `app/Support/Markdown.php` |
| Deployment per FTP/Filemanager | Push auf `main` → GitHub Actions → SSH → Docker |
| Cookie-Banner + Google Analytics nach Zustimmung | **kein Analytics, kein Banner** – Einbettungen laden erst auf Klick |

Der Import läuft über `php artisan import:legacy`. **Ohne `--ueberschreiben` fasst er vorhandene
Datensätze nicht an** – sobald in der Redaktion gearbeitet wurde, ist die Datenbank die Wahrheit
und nicht mehr die JSON-Datei. Beiträge werden über `posts.legacy_id` wiedererkannt; dieses Feld
trägt die 301-Weiterleitungen von `/pages/blog-post.html?id=N` und darf **nie neu vergeben
werden**.

## Entwickeln & Testen

```bash
php artisan serve      # → http://localhost:8000
npm run dev            # Vite, Assets + HMR
php artisan test       # 74 Tests
```

Beides muss laufen: ohne Vite fehlen Stylesheet und Schriften.

- **Datenbank:** lokal SQLite (`database/database.sqlite`), auf dem Server PostgreSQL.
  Migrationen müssen auf beiden laufen – keine SQLite- oder Postgres-Eigenheiten.
- **Redaktionszugang lokal:** `info@nils-digital.de` / `umbau-lokal`. Das ist der bewusst
  unbrauchbare Vorgabewert aus `DatabaseSeeder`; er greift nur, wenn `ADMIN_PASSWORT` leer und die
  Umgebung nicht `production` ist. In `production` legt der Seeder ohne gesetztes `ADMIN_PASSWORT`
  **gar keinen Zugang** an, sondern warnt – dort stattdessen `php artisan make:filament-user`.
- **Mail:** `MAIL_MAILER=log` – das Kontaktformular verschickt lokal nichts, die Mails landen in
  `storage/logs/laravel.log`. (Auf der alten Seite postete das Formular an die Produktions-URL;
  ein lokaler Test verschickte echte Mails. Das ist vorbei.)

## Architektur

### Seitengerüst
`components/layouts/oeffentlich.blade.php` ist das Grundgerüst aller öffentlichen Seiten. Es
zieht `<x-seo>`, die Schriften über `Vite::fonts()`, `<x-kopfzeile>` und `<x-fusszeile>` ein.

- **Kopfzeile:** Navigation als Liste im Markup, aktiver Punkt über `request()->routeIs()`.
  Untermenü und Menü auf schmalen Geräten laufen über `<details>` – aufklappbar ohne eine Zeile
  JavaScript, von Haus aus per Tastatur bedienbar und für Screenreader korrekt ausgezeichnet.
- **JavaScript** gibt es fast keins: `resources/js/app.js` enthält ausschließlich das Nachladen
  der Einbettungen auf Klick. Es gibt **kein Framework, kein Alpine, kein jQuery**. Neue
  Interaktion zuerst mit HTML und CSS versuchen (`<details>`, `:has()`, `:target`,
  scroll-driven animations); JavaScript nur, wenn es ohne wirklich nicht geht, und dann so, dass
  die Seite ohne JS benutzbar bleibt.

### Inhalte kommen aus der Datenbank
`/admin` (Filament) ist die Redaktionsschicht. **Alles, was auf der Seite steht, soll dort
pflegbar sein** – das ist eine ausdrückliche Anforderung, kein Nebenziel. Neue sichtbare Texte
gehören deshalb nicht fest ins Blade, sondern in ein Modell mit Filament-Resource.

| Tabelle | Modell | Inhalt |
|---|---|---|
| `posts` + `post_links` + `products` | `Post` | Blogbeiträge. `products` ist seit dem
  Ausdünnen leer – die Shop-Beiträge sind weg, die Tabelle bleibt für den Fall, dass wieder
  etwas verkauft wird |
| `categories` | `Category` | Blog-Kategorien inkl. Badge-Farben (`color`, `text_color`) |
| `projects` | `Project` | Referenzen, `is_featured` steuert die Startseite |
| `service_categories` + `services` | `Service` | Leistungen und Preise |
| `reviews` | `Review` | Kundenstimmen |
| `team_members` | `TeamMember` | Die Personen auf `/team` |

Badge-Farben stehen jetzt **als Spalte am Datensatz**, nicht mehr als `.cat-…`-CSS-Regel pro
Kategorie. Eine neue Kategorie braucht deshalb keine CSS-Änderung mehr.

### Kundenstimmen
Zwei Scopes mit unterschiedlichem Zweck:

- `Review::visible()` – alle sichtbaren, nach `position`. Grundlage für die **Gesamtbewertung**
  im Schema.org-Block.
- `Review::vorzeigbar()` – zusätzlich nur die **mit Text**, Sortierung entfernt. Grundlage für
  die **Anzeige**.

Die Startseite zeigt vier davon in zufälliger Reihenfolge (`inRandomOrder()`). Zwei Gründe: es
werden laufend mehr, und alle untereinander ließen die Seite endlos wachsen, während jede
Besucherin trotzdem immer dieselben sähe. **Die Zufallsauswahl darf nie in die
`aggregateRating` einfließen** – eine `reviewCount`, die bei jedem Aufruf schwankt, ist für
Suchmaschinen ein Warnzeichen. Deshalb bekommt die View beides: `$stimmen` (Auswahl) und
`$stimmenGesamt` (alle).

Bewertungen ohne Text sind normal – bei Google vergibt man oft nur Sterne. Sie zählen für den
Schnitt, erscheinen aber nicht als leere Kachel.

### Gestaltung

Design-Tokens stehen als Custom Properties im `@theme`-Block von `resources/css/app.css` und
werden daraus zu Tailwind-Klassen (`bg-flaeche`, `text-akzent`, `border-linie`). **Farben immer
über diese Tokens, nie hart kodiert** – auch nicht als `rgba()` im Markup.

```
--color-flaeche  #0d1117    Grundfläche      --color-text        #f9fafb
--color-flaeche-2 #111827   abgesetzt        --color-text-leise  #9ca3af
--color-karte    #1a1f27    Kacheln          --color-akzent      #00bcd4
--color-fuss     #0b0f14    Fußzeile         --color-akzent-hell #02c8e3
                                             --color-linie       rgba(255,255,255,.1)
```

Schriften werden über `laravel-vite-plugin/fonts` von Bunny **beim Bauen heruntergeladen und
selbst ausgeliefert** – kein Google-Fonts-Aufruf, eine Fremdverbindung weniger, ein Absatz
weniger in der Datenschutzerklärung. Neue Schnitte in `vite.config.js` ergänzen, nicht per
`<link>` ins Layout.

**Keine Emojis.** Sie werden von jedem Betriebssystem anders gezeichnet – ausgerechnet das
einzige durchgehend farbige Element der Seite wäre damit das, über das wir keine Kontrolle
haben. Stattdessen `<x-symbol name="chip" />`: Strichzeichnungen, die die Textfarbe erben.
Neue Symbole kommen in die Liste in `app/Support/Symbole.php`, keine Icon-Bibliothek – dort und
nicht im Blade, weil die Redaktion dieselben Namen zur Auswahl braucht. `services.icon` trägt
seit September 2026 Symbolnamen statt Emojis, das Feld in Filament ist eine Auswahl.
Einzelne Emojis sind geduldet, wo sie zu jemandes eigenen Worten gehören – etwa in einer
zitierten Kundenstimme oder einem persönlichen Satz im Blog („angeschrieben 😊").

Die Blogbeiträge sind danach einmal durchgegangen worden: aus Titeln, Teasern und
Zwischenüberschriften ist alles raus, `👉`- und `➡️`-Zeilen sind zu echten Markdown-Listen
geworden. Stehen geblieben sind zwölf Emojis in eigenen Sätzen. Für neue Beiträge gilt
dieselbe Trennung: **Dekoration nein, Stimme ja.** Ein Titel bekommt nie eines – er landet in
`<title>`, Open Graph und im Google-Snippet.

**Barrierefreiheit ist gesetzt, nicht optional:** `prefers-reduced-motion` schaltet in
`app.css` global Animationen und weiches Scrollen ab, es gibt einen Sprunglink, einen sichtbaren
Fokusrahmen und `aria-current` in der Navigation. Neue Bewegung muss sich in diese Regel fügen.

### SEO
`<x-seo>` rendert Titel, Description, Canonical, Open Graph, Twitter Card und JSON-LD
**serverseitig**. Das war der Kern des Umbaus: Crawler von WhatsApp, LinkedIn und Facebook führen
kein JavaScript aus, jeder geteilte Blogbeitrag zeigte deshalb früher „Blog – nils-digital" ohne
Bild.

Jede Seite übergibt `titel`, `beschreibung` und bei Bedarf `bild`, `typ` und `jsonld` an das
Layout. `sitemap.xml` und `robots.txt` erzeugt der `SitemapController` aus der Datenbank – nichts
davon wird von Hand gepflegt.

Alte Adressen leiten in `routes/web.php` per 301 auf ihr **Endziel**, nie über eine
Zwischenstation; `UmzugTest` geht die Liste aus `database/legacy/url-map.csv` vollständig durch.

### Formulare und Einbettungen
- **Kontaktformular** → `KontaktController` + `KontaktRequest`, versendet `KontaktAnfrage`
  (an Nils) und `KontaktBestaetigung` (an die Absenderin) über Laravels Mailer.
- **Projektanfrage** (Google Forms) und **Termine** (Google Calendar) sind Einbettungen.
  `<x-einbettung>` zeigt zunächst nur eine Vorschau; der iframe entsteht erst auf Klick, weil
  Google sonst schon beim Seitenaufruf Cookies setzt und die IP überträgt. Ohne JavaScript bleibt
  die Vorschau samt Link zum Anbieter stehen.
- Der frühere Spreadshop-Shop und die SunnyCam-Seite wurden **nicht** neu gebaut; ihre alten
  Adressen leiten auf die Startseite.

### Betrieb
`deploy/` enthält Dockerfile, Compose-Datei, `entrypoint.sh` und `deploy.sh`. Push auf `main`
löst `.github/workflows/deploy.yml` aus: Tests gegen PostgreSQL, dann über SSH das Skript auf dem
VPS. Der hinterlegte Schlüssel ist per erzwungenem Kommando auf genau dieses Skript festgenagelt –
eine Shell bekommt man damit nicht.

Zwei Fallstricke sind dort schon gelöst und sollten nicht „vereinfacht" werden:
`deploy.sh` kapselt seinen gesamten Ablauf in eine Funktion, weil `git reset --hard` das Skript
**während des Laufs überschreibt** und Bash Dateien nach Byte-Position nachliest. Und die
GitHub-Action versucht die SSH-Verbindung dreimal mit wachsender Pause, weil Hostinger wechselnde
Runner-Adressen gelegentlich abweist.

Traefik terminiert TLS und setzt für die Vorschau den `noindex`-Header (`ndweb-noindex@docker`).
Beim Domain-Umzug muss dieser Header weg – die `robots.txt` allein regelt das nicht.

## Arbeitsweise in diesem Repo

- Bestehende Dateien zuerst analysieren, dann Bericht geben.
- **Es gibt Git** – anders als früher. Änderungen sind rücknehmbar, Commits auf Deutsch,
  Betreffzeile ohne Präfix, im Rumpf das *Warum* statt des *Was*.
- Push auf `main` deployt sofort auf die Vorschau. Nichts pushen, was nicht laufen soll.
- `.env` und die Zugangsdaten darin niemals in Ausgaben, Commits oder Uploads streuen.
- Vor größeren Umbauten Freigabe abwarten.

## Offene Punkte

- **Domain-Umzug** `nils-digital.de` von Strato auf den VPS – der letzte große Schritt.
  Dabei `noindex` entfernen und die Sitemap bei Google einreichen.
- **Startseiten-Hero** hat noch kein Motiv, nur einen Farbverlauf als Platzhalter.
- **Startseiten-Texte** (Hero, „Was wir machen", Schlussabschnitt) stehen noch fest im Blade und
  müssen in die Redaktion wandern. Die Teamseite ist diesen Weg schon gegangen – ihre Migration
  taugt als Muster, weil sie die bestehenden Inhalte gleich mitnimmt.
- **Redaktionsoberfläche aufräumen:** die Resources für Projekte, Kundenstimmen, Kategorien und
  Leistungen sind noch der generierte Rohstand – englische Feldnamen, teils unsinnige Felder
  (`ProjectForm` bietet `image_fit` als Datei-Upload an, obwohl dort „cover" oder „contain"
  hineingehört). `PostForm` und `TeamMemberForm` zeigen, wie es aussehen soll. Ebenfalls offen:
  hochgeladene Bilder zeigen im Formular keine Miniatur, nur den Dateinamen.
- **Datenübertragung lokal → Server** für den Umzug. Der Deploy fasst Inhalte bewusst nicht
  an (`entrypoint.sh` migriert nur), es gibt also auch kein Werkzeug dafür. Gebraucht wird es
  genau einmal: kurz vor dem Domain-Umzug wird der lokale Stand geschlossen auf den Server
  gebracht. **Ab dann ist der Server die Wahrheit** und der Weg dreht sich um – lokal zieht man
  sich von dort.
- **Hinweis-/Popup-System** für Aktionen und Feiertage ist geplant, aber noch nicht gebaut.
- **Übersicht auf `/admin`:** das Filament-Dashboard ist noch der leere Vorgabezustand. Gewünscht
  sind Kennzahlen auf einen Blick – Anzahl Beiträge, Projekte, Kundenstimmen und Ähnliches.
- `legacy/` kann raus, sobald der Import endgültig abgeschlossen ist.
