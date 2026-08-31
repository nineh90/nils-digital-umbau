<?php
/**
 * Startseite – index.php
 * Inhalt: Hero, Leistungsübersicht, Vertrauensbereich, Kontakt-CTA
 */

// Seitentitel und Beschreibung für den Header
$seiten_titel = 'Alltagsbegleitung für Senioren';
$seiten_beschreibung = 'Andrea Brackemeyer – Alltagsbegleitung für Senioren in Ihrer Nähe. Über 20 Jahre Erfahrung. Gespräche, Begleitung, Unterstützung im Alltag. Jetzt Kontakt aufnehmen.';

// Header einbinden
include 'includes/header.php';
?>

<!-- ===== HAUPTINHALT DER STARTSEITE ===== -->
<main id="hauptinhalt">

    <!-- ===== HERO-BEREICH ===== -->
    <!-- Großer Willkommensbereich mit Bild, Motto und Handlungsaufforderung -->
    <section class="hero" aria-label="Willkommen">
        <div class="hero-bild-wrapper">
            <!-- TODO [PLATZHALTER]: Hero-Bild durch echtes Foto von Andrea (mit Seniorin) ersetzen → /images/hero.jpg o.ä. -->
            <img
                src="<?= $base ?>/images/alltagsbegleitung_brackemeyer_bsp.png"
                alt="Freundliche Alltagsbegleiterin mit Seniorin im Gespräch"
                class="hero-bild"
                width="1400"
                height="600"
                fetchpriority="high"
            >
            <div class="hero-overlay"></div>
        </div>

        <div class="container hero-inhalt">
            <div class="hero-text">
                <p class="hero-vortitel">Alltagsbegleitung in der Region Tecklenburg</p>
                <h1 class="hero-titel">
                    Andrea – <span class="akzent">Alltag mit Herz</span>
                </h1>
                <p class="hero-slogan">
                    Ich nehme mir Zeit für Sie –<br>
                    mit Herz, Respekt und Verlässlichkeit.
                </p>
                <div class="hero-buttons">
                    <a href="https://nils-digital.de/pages/kontakt.html" target="_blank" rel="noopener" class="btn btn-primaer btn-gross">
                        Jetzt Kontakt aufnehmen
                    </a>
                    <a href="<?= $base ?>/pages/leistungen.php" class="btn btn-sekundaer btn-gross">
                        Meine Leistungen
                    </a>
                </div>
                <!-- Telefonnummer direkt im Hero – wichtig für ältere Besucher -->
                <p class="hero-telefon">
                    Oder rufen Sie mich an:
                    <a href="tel:015161657136"><strong>0151 61 65 71 36</strong></a>
                </p>
            </div>
        </div>

        <!-- Dekoratives Herz – nur Desktop, zeichnet sich beim Laden -->
        <div class="hero-herz-deko" aria-hidden="true">
            <svg viewBox="0 0 200 185" xmlns="http://www.w3.org/2000/svg">
                <path class="hero-herz-pfad" d="M100,170 C55,138 8,105 8,62 C8,28 30,8 58,8 C74,8 88,18 100,34 C112,18 126,8 142,8 C170,8 192,28 192,62 C192,105 145,138 100,170 Z"/>
            </svg>
        </div>

    </section>
    <!-- ===== ENDE HERO ===== -->


    <!-- ===== KERNWERTE-STREIFEN ===== -->
    <section class="kernwerte-streifen" aria-label="Unsere Werte">
        <div class="container">
            <div class="kernwerte-inner">

                <div class="kernwert">
                    <span class="kernwert-icon-wrapper" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </span>
                    <h2 class="kernwert-titel">ZEIT</h2>
                </div>

                <span class="kernwert-trenner" aria-hidden="true">♥</span>

                <div class="kernwert">
                    <span class="kernwert-icon-wrapper" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </span>
                    <h2 class="kernwert-titel">NÄHE</h2>
                </div>

                <span class="kernwert-trenner" aria-hidden="true">♥</span>

                <div class="kernwert">
                    <span class="kernwert-icon-wrapper" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <polyline points="9 12 11 14 15 10"/>
                        </svg>
                    </span>
                    <h2 class="kernwert-titel">UNTERSTÜTZUNG</h2>
                </div>

            </div>
        </div>
    </section>
    <!-- ===== ENDE KERNWERTE ===== -->


    <!-- ===== LEISTUNGSÜBERSICHT ===== -->
    <section class="leistungen-uebersicht" aria-labelledby="leistungen-ueberschrift">
        <div class="container">

            <div class="abschnitt-kopf">
                <h2 id="leistungen-ueberschrift" class="abschnitt-titel">Was ich für Sie tue</h2>
                <p class="abschnitt-untertitel">
                    Ich unterstütze Sie im Alltag – immer angepasst an Ihre Wünsche und Bedürfnisse.
                    <strong>Keine Pflegeleistungen</strong>, aber echte menschliche Begleitung.
                </p>
            </div>

            <!-- Leistungskarten -->
            <div class="karten-raster">

                <!-- Karte 1: Zeit & Gesellschaft -->
                <article class="leistungs-karte">
                    <h3 class="karte-titel">Zeit & Gesellschaft</h3>
                    <p class="karte-text">
                        Gespräche führen, zuhören, vorlesen und gemeinsame Aktivitäten erleben –
                        weil Einsamkeit nicht sein muss.
                    </p>
                    <a href="<?= $base ?>/pages/leistungen.php#gesellschaft" class="karte-link">Mehr erfahren →</a>
                </article>

                <!-- Karte 2: Aktiv Bleiben -->
                <article class="leistungs-karte">
                    <h3 class="karte-titel">Aktiv Bleiben</h3>
                    <p class="karte-text">
                        Spiele, Gedächtnistraining, kreative Tätigkeiten, Musik und
                        gemeinsame Spaziergänge für Körper und Geist.
                    </p>
                    <a href="<?= $base ?>/pages/leistungen.php#aktiv" class="karte-link">Mehr erfahren →</a>
                </article>

                <!-- Karte 3: Begleitung -->
                <article class="leistungs-karte">
                    <h3 class="karte-titel">Begleitung</h3>
                    <p class="karte-text">
                        Ich begleite Sie zum Arzt, zur Apotheke, zum Friedhof, in die Kirche,
                        zum Einkaufen und zu Behörden.
                    </p>
                    <a href="<?= $base ?>/pages/leistungen.php#begleitung" class="karte-link">Mehr erfahren →</a>
                </article>

                <!-- Karte 4: Unterstützung im Alltag -->
                <article class="leistungs-karte">
                    <h3 class="karte-titel">Alltags&shy;unterstützung</h3>
                    <p class="karte-text">
                        Flexible Hilfe im Alltag – individuell abgestimmt und
                        persönlich vor Ort besprochen.
                    </p>
                    <a href="<?= $base ?>/pages/leistungen.php#alltag" class="karte-link">Mehr erfahren →</a>
                </article>

            </div>

            <div class="leistungen-cta">
                <a href="<?= $base ?>/pages/leistungen.php" class="btn btn-primaer">Alle Leistungen im Überblick</a>
            </div>

        </div>
    </section>
    <!-- ===== ENDE LEISTUNGSÜBERSICHT ===== -->


    <!-- ===== LOGO & SPRÜCHE ===== -->
    <?php include 'includes/logo_sprueche.php'; ?>
    <!-- ===== ENDE LOGO & SPRÜCHE ===== -->


    <!-- ===== VERTRAUENSBEREICH ===== -->
    <!-- Erfahrung und Qualifikationen – wichtig für Vertrauensaufbau -->
    <section class="vertrauen" aria-labelledby="vertrauen-ueberschrift">
        <div class="container vertrauen-inner">

            <!-- TODO [PLATZHALTER]: Portraitfoto von Andrea ersetzen – aktuell: picsum.photos-Dummy → /images/andrea-portrait.jpg -->
            <div class="vertrauen-bild-wrapper">
                <img
                    src="https://picsum.photos/seed/andrea-portrait2/500/600"
                    alt="Andrea Brackemeyer – Ihre Alltagsbegleiterin"
                    class="vertrauen-bild"
                    width="500"
                    height="600"
                    loading="lazy"
                >
            </div>

            <!-- Text und Vertrauenssignale -->
            <div class="vertrauen-text">
                <p class="vertrauen-vortitel">Über mich</p>
                <h2 id="vertrauen-ueberschrift" class="abschnitt-titel">
                    Verlässlichkeit aus Überzeugung
                </h2>
                <p class="vertrauen-intro">
                    Ich bin Andrea Brackemeyer – aufgewachsen in der Region Tecklenburg,
                    verwurzelt mit den Menschen hier vor Ort. Meine Arbeit ist meine Berufung.
                </p>

                <!-- Vertrauenssignale als Liste -->
                <ul class="vertrauens-liste">
                    <li>
                        <span class="check-icon" aria-hidden="true">✓</span>
                        <span><strong>Über 20 Jahre</strong> Berufserfahrung mit Senioren</span>
                    </li>
                    <li>
                        <span class="check-icon" aria-hidden="true">✓</span>
                        <span><strong>Staatlich examinierte</strong> Altenpflegerin</span>
                    </li>
                    <li>
                        <span class="check-icon" aria-hidden="true">✓</span>
                        <span>Erfahrung als <strong>Pflegedienstleitung</strong></span>
                    </li>
                    <li>
                        <span class="check-icon" aria-hidden="true">✓</span>
                        <span>Abrechnung über <strong>Pflegekasse möglich</strong> (§45b SGB XI)</span>
                    </li>
                    <li>
                        <span class="check-icon" aria-hidden="true">✓</span>
                        <span>Ortskenntnis in der <strong>Region Tecklenburg und Münsterland</strong></span>
                    </li>
                </ul>

                <a href="<?= $base ?>/pages/about.php" class="btn btn-primaer">Mehr über mich</a>
            </div>

        </div>
    </section>
    <!-- ===== ENDE VERTRAUENSBEREICH ===== -->


    <!-- ===== ABRECHNUNG HINWEIS ===== -->
    <!-- Kurzer Hinweis auf Kostenübernahme – senkt die Hemmschwelle zur Kontaktaufnahme -->
    <section class="abrechnung-hinweis" aria-labelledby="abrechnung-hinweis-titel">
        <div class="container abrechnung-hinweis-inner">
            <div class="abrechnung-text">
                <h2 id="abrechnung-hinweis-titel">Über Pflegekasse abrechenbar</h2>
                <p>
                    Meine Leistungen können über den <strong>Entlastungsbetrag §45b SGB XI</strong>
                    mit der Pflegekasse abgerechnet werden – bis zu <strong>125 € monatlich</strong>
                    übernimmt die Kasse. Auch Selbstzahler sind herzlich willkommen.
                </p>
                <a href="<?= $base ?>/pages/abrechnung.php" class="btn btn-outline">Zur Abrechnung & Kosten</a>
            </div>
        </div>
    </section>
    <!-- ===== ENDE ABRECHNUNG HINWEIS ===== -->


    <!-- ===== KONTAKT-CTA ===== -->
    <!-- Abschließende Handlungsaufforderung -->
    <section class="kontakt-cta" aria-labelledby="kontakt-cta-titel">
        <div class="container kontakt-cta-inner">
            <h2 id="kontakt-cta-titel">Lassen Sie uns ins Gespräch kommen</h2>
            <p>
                Ich freue mich auf Ihren Anruf oder Ihre Nachricht –
                und darauf, für Sie da zu sein.
            </p>
            <div class="cta-buttons">
                <a href="tel:015161657136" class="btn btn-primaer btn-gross">
                    0151 61 65 71 36
                </a>
                <a href="https://nils-digital.de/pages/kontakt.html" target="_blank" rel="noopener" class="btn btn-sekundaer btn-gross">
                    Nachricht schreiben
                </a>
            </div>
        </div>
    </section>
    <!-- ===== ENDE KONTAKT-CTA ===== -->

</main>
<!-- ===== ENDE HAUPTINHALT ===== -->

<?php
// Footer einbinden
include 'includes/footer.php';
?>
