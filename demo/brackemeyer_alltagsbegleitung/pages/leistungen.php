<?php
/**
 * Leistungen – leistungen.php
 */

$seiten_titel = 'Leistungen';
$seiten_beschreibung = 'Alltagsbegleitung für Senioren: Zeit & Gesellschaft, aktiv bleiben, Begleitung zu Terminen und Unterstützung im Alltag. Keine Pflegeleistungen.';

include '../includes/header.php';
?>

<main id="hauptinhalt">

    <!-- ===== SEITENHEADER ===== -->
    <section class="seiten-hero" aria-label="Seitenüberschrift">
        <div class="container">
            <p class="seiten-hero-vortitel">Was ich für Sie tue</p>
            <h1 class="seiten-hero-titel">Meine Leistungen</h1>
            <p class="seiten-hero-untertitel">
                Individuell, verlässlich und genau so, wie Sie es brauchen –
                für Senioren in der Region Tecklenburg und Münsterland.
            </p>
        </div>
    </section>


    <!-- ===== HINWEIS: KEINE PFLEGE ===== -->
    <div class="container" style="padding-top: 2rem;">
        <div class="hinweis-keine-pflege hinweis-keine-pflege--seite" aria-label="Wichtiger Hinweis">
            <div class="hinweis-inner">
                <div class="hinweis-text">
                    <strong>Wichtig:</strong> Meine Leistungen sind Alltagsbegleitung –
                    <strong>keine Pflegeleistungen</strong>. Medizinische oder pflegerische
                    Tätigkeiten gehören nicht dazu.
                </div>
            </div>
        </div>
    </div>


    <!-- ===== LEISTUNG 1: ZEIT & GESELLSCHAFT ===== -->
    <section class="leistung-detail" id="gesellschaft" aria-labelledby="leistung-gesellschaft-titel">
        <div class="container leistung-detail-inner">

            <div class="leistung-bild-wrapper">
                <img
                    src="<?= $base ?>/images/brackemeyer_alltagsbegleitung_gesellschaft.png"
                    alt="Betreuerin und Seniorin schauen gemeinsam Fotoalbum an und unterhalten sich beim Kaffee"
                    class="leistung-bild"
                    width="520"
                    height="380"
                    loading="lazy"
                >
            </div>

            <div class="leistung-text">
                <h2 id="leistung-gesellschaft-titel" class="abschnitt-titel">
                    Zeit & Gesellschaft
                </h2>
                <p class="leistung-intro">
                    Einsamkeit ist eine der größten Herausforderungen im Alter.
                    Ich bin einfach für Sie da – als Gesprächspartnerin, Zuhörerin
                    und Begleiterin im Alltag.
                </p>
                <ul class="leistung-liste">
                    <li>Gespräche führen über Alltag, Erinnerungen und Interessen</li>
                    <li>Zuhören – einfach da sein</li>
                    <li>Vorlesen aus Büchern, Zeitungen oder Briefen</li>
                    <li>Gemeinsam Fotos anschauen und Erinnerungen teilen</li>
                    <li>Gesellschaft bei Mahlzeiten</li>
                    <li>Gemeinsame Aktivitäten nach Ihren Wünschen</li>
                </ul>
            </div>

        </div>
    </section>


    <!-- ===== LEISTUNG 2: AKTIV BLEIBEN ===== -->
    <section class="leistung-detail leistung-detail--beige" id="aktiv" aria-labelledby="leistung-aktiv-titel">
        <div class="container leistung-detail-inner leistung-detail-inner--umgekehrt">

            <div class="leistung-text">
                <h2 id="leistung-aktiv-titel" class="abschnitt-titel">
                    Aktiv Bleiben
                </h2>
                <p class="leistung-intro">
                    Körper und Geist brauchen Bewegung und Anregung – in jedem Alter.
                    Gemeinsam finden wir Aktivitäten, die Ihnen Freude machen und
                    Ihre Lebensqualität steigern.
                </p>
                <ul class="leistung-liste">
                    <li>Gesellschaftsspiele und Kartenspiele</li>
                    <li>Gedächtnistraining und Denksport</li>
                    <li>Kreative Tätigkeiten (Basteln, Malen, Handarbeiten)</li>
                    <li>Musik hören oder gemeinsam singen</li>
                    <li>Spaziergänge in der Natur und frische Luft</li>
                    <li>Leichte Bewegungsübungen im Sitzen</li>
                </ul>
            </div>

            <div class="leistung-bild-wrapper">
                <img
                    src="<?= $base ?>/images/brackemeyer_alltagsbegleitung_aktiv_bleiben.png"
                    alt="Collage: Seniorin beim Spielen, Malen, Kartenspielen, Musizieren, Handarbeiten und Spazierengehen"
                    class="leistung-bild"
                    width="520"
                    height="380"
                    loading="lazy"
                >
            </div>

        </div>
    </section>


    <!-- ===== LEISTUNG 3: BEGLEITUNG ===== -->
    <section class="leistung-detail" id="begleitung" aria-labelledby="leistung-begleitung-titel">
        <div class="container leistung-detail-inner">

            <div class="leistung-bild-wrapper">
                <img
                    src="<?= $base ?>/images/brackemeyer_alltagsbegleitung_senioren_begleitung.png"
                    alt="Collage: Begleitung zu Arztpraxis, Apotheke, Kirche, Friedhof, Wochenmarkt, Bürgeramt und Supermarkt"
                    class="leistung-bild"
                    width="520"
                    height="380"
                    loading="lazy"
                >
            </div>

            <div class="leistung-text">
                <h2 id="leistung-begleitung-titel" class="abschnitt-titel">
                    Begleitung
                </h2>
                <p class="leistung-intro">
                    Viele wichtige Wege werden schwieriger, wenn man allein ist.
                    Ich begleite Sie – verlässlich, pünktlich und mit der nötigen Zeit.
                </p>
                <ul class="leistung-liste">
                    <li>Arztbesuche und medizinische Termine</li>
                    <li>Apotheke und Besorgungen</li>
                    <li>Kirchgänge und Gottesdienste</li>
                    <li>Friedhofsbesuche</li>
                    <li>Einkaufen (Supermarkt, Markt, Fachgeschäfte)</li>
                    <li>Behördengänge und Ämter</li>
                    <li>Sonstige Termine nach Absprache</li>
                </ul>
            </div>

        </div>
    </section>


    <!-- ===== LEISTUNG 4: ALLTAGSUNTERSTÜTZUNG ===== -->
    <section class="leistung-detail leistung-detail--beige" id="alltag" aria-labelledby="leistung-alltag-titel">
        <div class="container leistung-detail-inner leistung-detail-inner--umgekehrt">

            <div class="leistung-text">
                <h2 id="leistung-alltag-titel" class="abschnitt-titel">
                    Unterstützung im Alltag
                </h2>
                <p class="leistung-intro">
                    Manchmal braucht man jemanden, der einfach hilft – flexibel und
                    unkompliziert. Was ich konkret tun kann, besprechen wir persönlich bei Ihnen zu Hause.
                </p>
                <ul class="leistung-liste">
                    <li>Flexible Hilfe, individuell abgestimmt</li>
                    <li>Unterstützung bei Post und Briefen</li>
                    <li>Hilfe bei der Nutzung von Telefon und einfachen Geräten</li>
                    <li>Gesellschaft beim Warten (Behörden, Arzt etc.)</li>
                    <li>Organisation kleiner Alltagsaufgaben</li>
                    <li>Alles weitere: sprechen Sie mich einfach an!</li>
                </ul>
            </div>

            <div class="leistung-bild-wrapper">
                <img
                    src="<?= $base ?>/images/brackemeyer_alltagsbegleitung_unterstuetzung.png"
                    alt="Betreuerin hilft Seniorin beim Smartphone und erklärt ihr Briefe und Dokumente"
                    class="leistung-bild"
                    width="520"
                    height="380"
                    loading="lazy"
                >
            </div>

        </div>
    </section>


    <!-- ===== ABRECHNUNG HINWEIS ===== -->
    <section class="abrechnung-hinweis" aria-labelledby="abr-hinweis-titel">
        <div class="container abrechnung-hinweis-inner">
            <div class="abrechnung-text">
                <h2 id="abr-hinweis-titel">Viele Leistungen über Pflegekasse abrechenbar</h2>
                <p>
                    Meine Alltagsbegleitung kann über den <strong>Entlastungsbetrag §45b SGB XI</strong>
                    mit der Pflegekasse abgerechnet werden. Mehr dazu auf der Seite Abrechnung & Kosten.
                </p>
                <div style="display:flex; gap:1rem; flex-wrap:wrap; justify-content:center;">
                    <a href="<?= $base ?>/pages/abrechnung.php" class="btn btn-outline">Zur Abrechnung & Kosten</a>
                    <a href="https://nils-digital.de/pages/kontakt.html" target="_blank" rel="noopener" class="btn btn-primaer">Jetzt anfragen</a>
                </div>
            </div>
        </div>
    </section>


    <!-- ===== KONTAKT-CTA ===== -->
    <section class="kontakt-cta" aria-labelledby="leistungen-cta-titel">
        <div class="container kontakt-cta-inner">
            <h2 id="leistungen-cta-titel">Interesse? Rufen Sie mich einfach an.</h2>
            <p>
                Ich beantworte gerne Ihre Fragen und schaue mit Ihnen,
                wie ich am besten helfen kann.
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

</main>

<?php include '../includes/logo_sprueche.php'; ?>

<?php include '../includes/footer.php'; ?>
