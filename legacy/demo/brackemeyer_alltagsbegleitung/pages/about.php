<?php
/**
 * Über mich – about.php
 */

$seiten_titel = 'Über mich';
$seiten_beschreibung = 'Lernen Sie Andrea Brackemeyer kennen – staatlich examinierte Altenpflegerin mit über 20 Jahren Erfahrung. Ihr persönlicher Kontakt für Alltagsbegleitung in Ihrer Nähe.';

include '../includes/header.php';
?>

<main id="hauptinhalt">

    <!-- ===== SEITENHEADER ===== -->
    <section class="seiten-hero" aria-label="Seitenüberschrift">
        <div class="container">
            <p class="seiten-hero-vortitel">Lernen Sie mich kennen</p>
            <h1 class="seiten-hero-titel">Über mich</h1>
            <p class="seiten-hero-untertitel">
                Ein Mensch hinter der Arbeit – mit Herz, Erfahrung und echtem Interesse an Ihrem Wohlergehen.
            </p>
        </div>
    </section>

    <!-- ===== PORTRAIT UND EINLEITUNG ===== -->
    <section class="about-intro" aria-labelledby="about-intro-titel">
        <div class="container about-intro-inner">

            <div class="about-bild-wrapper">
                <!-- TODO [PLATZHALTER]: Portraitfoto von Andrea ersetzen – aktuell: picsum.photos-Dummy → /images/andrea-portrait.jpg -->
                <img
                    src="https://picsum.photos/seed/andrea-portrait2/480/580"
                    alt="Andrea Brackemeyer – Alltagsbegleiterin"
                    class="about-bild"
                    width="480"
                    height="580"
                    fetchpriority="high"
                >
                <!-- Zitat-Karte über dem Bild -->
                <div class="about-zitat-karte">
                    <p class="about-zitat">"Ich tue das, was ich tue, weil ich es wirklich liebe."</p>
                    <p class="about-zitat-name">– Andrea Brackemeyer</p>
                </div>
            </div>

            <div class="about-text">
                <p class="vertrauen-vortitel">Hallo, ich bin Andrea</p>
                <h2 id="about-intro-titel" class="abschnitt-titel">
                    Verlässlichkeit aus Überzeugung
                </h2>

                <p>
                    Ich bin Andrea Brackemeyer – aufgewachsen in der Region Tecklenburg,
                    verwurzelt mit den Menschen hier vor Ort. Ich kenne die Gegend, die Wege
                    und die kleinen Dinge, die das Leben angenehmer machen.
                </p>
                <p>
                    Nach über <strong>20 Jahren in der Seniorenbetreuung</strong> weiß ich:
                    Was ältere Menschen wirklich brauchen, ist echte
                    <strong>Zuwendung, Zeit und Verlässlichkeit</strong>.
                </p>
                <p>
                    Deshalb bin ich als Alltagsbegleiterin selbstständig – damit ich jedem Menschen
                    genau so viel Zeit geben kann, wie er wirklich braucht. Ohne Hektik, ohne Eile.
                </p>

                <!-- Qualifikationen als Vertrauenssignale -->
                <ul class="vertrauens-liste" style="margin-top: 1.5rem;">
                    <li>
                        <span class="check-icon" aria-hidden="true">✓</span>
                        <span><strong>Staatlich examinierte Altenpflegerin</strong></span>
                    </li>
                    <li>
                        <span class="check-icon" aria-hidden="true">✓</span>
                        <span><strong>Über 20 Jahre</strong> Berufserfahrung mit Senioren</span>
                    </li>
                    <li>
                        <span class="check-icon" aria-hidden="true">✓</span>
                        <span>Erfahrung als <strong>Pflegedienstleitung</strong></span>
                    </li>
                    <li>
                        <span class="check-icon" aria-hidden="true">✓</span>
                        <span><strong>Ortskenntnis</strong> in der Region Tecklenburg und gewachsenes Netzwerk vor Ort</span>
                    </li>
                </ul>
            </div>

        </div>
    </section>


    <!-- ===== MEIN ANSATZ ===== -->
    <section class="mein-ansatz" aria-labelledby="ansatz-titel">
        <div class="container">

            <div class="abschnitt-kopf">
                <h2 id="ansatz-titel" class="abschnitt-titel">So arbeite ich</h2>
                <p class="abschnitt-untertitel">
                    Meine Arbeit beginnt nicht mit einer Checkliste, sondern mit einem Gespräch.
                </p>
            </div>

            <div class="ansatz-karten">

                <div class="ansatz-karte">
                    <h3>Erstes Kennenlernen</h3>
                    <p>
                        Am Anfang steht ein persönliches Gespräch – bei Ihnen zu Hause,
                        ganz unverbindlich. Ich möchte verstehen, was Sie beschäftigt
                        und wie ich helfen kann.
                    </p>
                </div>

                <div class="ansatz-karte">
                    <h3>Individuelle Planung</h3>
                    <p>
                        Keine zwei Menschen sind gleich. Gemeinsam besprechen wir, welche
                        Unterstützung Sie sich wünschen – und passen das jederzeit an
                        Ihre Bedürfnisse an.
                    </p>
                </div>

                <div class="ansatz-karte">
                    <h3>Verlässliche Begleitung</h3>
                    <p>
                        Sie können sich auf mich verlassen – pünktlich, zuverlässig und
                        immer mit echtem Interesse an Ihrem Wohlbefinden.
                        Ich bin für Sie da.
                    </p>
                </div>

            </div>

        </div>
    </section>


    <!-- ===== WICHTIGER HINWEIS: KEINE PFLEGE ===== -->
    <section class="hinweis-keine-pflege" aria-label="Wichtiger Hinweis">
        <div class="container hinweis-inner">
            <div class="hinweis-text">
                <h2>Wichtiger Hinweis</h2>
                <p>
                    Ich bin Alltagsbegleiterin – <strong>keine Pflegekraft</strong>.
                    Meine Leistungen umfassen Gesellschaft, Begleitung und Alltagsunterstützung,
                    aber <strong>keine medizinischen oder pflegerischen Tätigkeiten</strong>.
                    Bei Pflegebedarf helfe ich Ihnen gerne, die passenden Ansprechpartner zu finden.
                </p>
            </div>
        </div>
    </section>


    <!-- ===== KONTAKT-CTA ===== -->
    <section class="kontakt-cta" aria-labelledby="about-cta-titel">
        <div class="container kontakt-cta-inner">
            <h2 id="about-cta-titel">Möchten Sie mich persönlich kennenlernen?</h2>
            <p>
                Ich freue mich auf ein unverbindliches Erstgespräch bei Ihnen zu Hause –
                in der Region Tecklenburg und dem nördlichen Münsterland.
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
