# Live-Gang: was noch zu tun ist

Stand der Bestandsaufnahme: **1. Oktober 2026**. Die Liste wird von oben nach unten
abgearbeitet. Wer etwas erledigt, hakt es ab und schreibt das Datum dahinter.

**N** = braucht Nils (Entscheidung, Text oder Zugang), alles andere ist Bauarbeit.

## Schon geprüft und in Ordnung

- Alle elf Adressen der alten Sitemap leiten per 301 direkt aufs Endziel.
- Alte Beitragsadressen (`/pages/blog-post.html?id=N`) führen zum neuen Beitrag,
  gelöschte zur Blogübersicht.
- Bildpfade `/assets/images/…` sind unverändert – Bildersuche und geteilte Links halten.
- Die Anwendung läuft bereits auf dem VPS; `robots.txt` und `deploy.sh` schalten
  anhand der Domain von Vorschau auf Produktion um.
- Kevins Seite (`kevins-werkstatt.nils-digital.de`) liegt auf einem eigenen Server
  und ist vom Umzug nicht betroffen.

## 1 · Kleinigkeiten vorweg

- [x] Kevins Werkstatt auf `/team` verlinken, Feld in der Redaktion (01.10.2026)
- [x] Alte interne Links in den Beiträgen umgestellt: `/pages/webdesign-leistung.html`
      → `/leistungen`, `/pages/projektfragebogen.html` → `/projektanfrage` (01.10.2026)
- [x] Kevin mit Foto und vollem Namen „Kevin Herrmann" auf `/team` (02.10.2026)
- [x] Nils' Rollenzeile: „Gründer, Entwickler & Ansprechpartner" statt
      „Gründer & Lead-Entwickler" (02.10.2026)
- [x] Sunny aus den strukturierten Daten genommen – auf der Seite bleibt sie.
      Im September war bewusst entschieden worden, sie drinzulassen; Nils hat
      das am 02.10.2026 umgekehrt.
- [x] Teamseite: Seitenkopf nennt jetzt auch Sunny („Und Sunny, die aufpasst."),
      die Beschreibung für Google bleibt bei Nils und Kevin (02.10.2026)
- [x] Favicon: `favicon.ico` in drei Größen, dazu die Symbole für Android und
      Apple unter ihren alten Adressen (02.10.2026)

## 2 · Kontaktformular scharf schalten

- [ ] Anfragen in der Datenbank speichern (Tabelle für Anfragen, Ansicht in der
      Redaktion). Heute wird nur verschickt – scheitert der Versand, ist die
      Anfrage weg und das Formular meldet trotzdem Erfolg.
- [ ] Fehlgeschlagene Mails sichtbar machen (Dashboard-Hinweis)
- [ ] Google-App-Passwort für `info@nils-digital.de` erzeugen und in `deploy/.env`
      auf dem Server eintragen (`MAIL_MAILER=smtp`, `MAIL_USERNAME`,
      `MAIL_PASSWORD`) – **N**. Nicht ins Repo, nicht in den Chat.
- [ ] Probeanfrage auf der Vorschau: kommt die Anfrage an, kommt die Bestätigung
      an, landet keine im Spam? Die Domain weist per DMARC alles ab, was nicht
      über Google läuft.

## 3 · Eigener Projektfragebogen statt Google Forms

Braucht vor dem Bau eine Freigabe – größerer Umbau. Muss den Live-Gang nicht
aufhalten: die Google-Einbettung funktioniert und lädt erst auf Klick.

- [ ] Fragen festlegen – **N**. Der alte Bogen hat 17 Fragen und fragt
      Wartungsstufen ab, die es nicht mehr gibt; er sollte zu Abo und Festpreis
      passen und abfragen, ob ein Unternehmen anfragt (brauchen die AGB).
- [ ] Mehrstufiges Formular ohne JavaScript, landet in derselben Tabelle wie die
      Kontaktanfragen
- [ ] Benachrichtigung an Nils, Bestätigung an die anfragende Person
- [ ] Ansicht und Status in der Redaktion (neu, in Arbeit, erledigt)
- [ ] `/projektanfrage` umstellen, Google-Einbettung entfernen

## 4 · Rechtstexte – N

- [ ] **Datenschutzerklärung**: nennt STRATO als Hoster und den Spreadshop, aber
      weder Hostinger noch die Google-Einbettungen (Kalender, bis Schritt 3 auch
      der Fragebogen) noch den Mailversand über Google.
- [ ] **AGB**: für Projektgeschäft geschrieben (50/50), das Abo steht mit dem
      Live-Gang öffentlich. Es fehlen Laufzeit, Kündigung, Zahlungsverzug,
      Umsatzsteuer-Klausel.
- [ ] Impressum gegenlesen

## 5 · Inhalte

- [ ] Fallstudie Lerndex: zwei offene Stellen im Entwurf
      (`storage/app/fallstudien/lerndex.md`) – **N**
- [ ] Weitere Fallstudien – **N**. Ohne Text bleiben die Projektseiten auf `noindex`.
- [ ] Drei neue Kundenstimmen eintragen – **N** (Texte)
- [ ] Startseiten-Texte in die Redaktion (kann auch nach dem Live-Gang)
- [ ] Leere Kategorien (Allgemein, Kooperationen, Shop) löschen oder behalten – **N**

## 6 · Umschalten vorbereiten

- [ ] Traefik-Regel: `nils-digital.de`, `www` → Weiterleitung auf die nackte
      Domain, `neu.` → Weiterleitung auf die Hauptdomain
- [ ] `APP_URL` in `deploy/.env` auf dem Server umstellen
- [ ] Die beiden `noindex`-Zeilen in `deploy/docker-compose.yml` entfernen
- [ ] Sicherung für Datenbank und hochgeladene Bilder einrichten – ab dem
      Umschalten ist der Server die Wahrheit
- [ ] Inhalte frisch ausgeben (`nd:inhalt-ausgeben`), pushen, auf dem Server
      einlesen (`nd:inhalt-einlesen`)
- [ ] Große Bilder verkleinern (`sunny.jpg` 4 MB, mehrere Logos über 1 MB)

## 7 · Der Umschalttag

- [ ] Vorher die DNS-Laufzeit bei Strato niedrig stellen
- [ ] A-Eintrag von `nils-digital.de` auf den VPS (187.124.178.193)
- [ ] **AAAA-Eintrag löschen** – er zeigt auf den alten Webspace, der VPS hat
      keinen. Bleibt er stehen, sehen IPv6-Besucher weiter die alte Seite.
- [ ] MX, SPF, DKIM, DMARC und Kevins Subdomain **nicht anfassen**
- [ ] Prüfen: Zertifikat da, `www` leitet weiter, kein `noindex` im Antwortkopf,
      `robots.txt` nennt die Sitemap, Stichprobe alter Adressen
- [ ] Sitemap in der Search Console einreichen, Startseite neu prüfen lassen
- [ ] Alte Seite bei Strato liegen lassen – der Rückweg ist ein DNS-Eintrag.
      Strato-Paket nicht kündigen, das DNS liegt dort.

## 8 · Danach

- [ ] Links in Google-Unternehmensprofil, LinkedIn, Instagram, Mail-Signatur prüfen
- [ ] Search Console nach zwei Wochen ansehen: 404er, Abdeckung
- [ ] n8n an den Feed hängen
- [ ] `legacy/` entfernen
- [ ] `actions/checkout` und `setup-node` auf `@v5`
- [ ] `CLAUDE.md` nachziehen (Testanzahl, offene Punkte)
