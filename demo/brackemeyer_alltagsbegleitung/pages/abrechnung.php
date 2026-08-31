<?php
/**
 * Abrechnung & Kosten – abrechnung.php
 */

$seiten_titel = 'Abrechnung & Kosten';
$seiten_beschreibung = 'Alltagsbegleitung über Pflegekasse abrechnen: Entlastungsbetrag §45b SGB XI bis 125 € monatlich. Auch für Selbstzahler. Infos zu Kosten und Abrechnung.';

include '../includes/header.php';
?>

<main id="hauptinhalt">

    <!-- ===== SEITENHEADER ===== -->
    <section class="seiten-hero" aria-label="Seitenüberschrift">
        <div class="container">
            <p class="seiten-hero-vortitel">Transparent und fair</p>
            <h1 class="seiten-hero-titel">Abrechnung & Kosten</h1>
            <p class="seiten-hero-untertitel">
                Meine Leistungen können in vielen Fällen von der Pflegekasse übernommen werden –
                und ich helfe Ihnen dabei, das unkompliziert in die Wege zu leiten.
            </p>
        </div>
    </section>


    <!-- ===== HAUPTINHALT: ZWEI WEGE ===== -->
    <section class="abrechnung-wege" aria-labelledby="wege-titel">
        <div class="container">

            <div class="abschnitt-kopf">
                <h2 id="wege-titel" class="abschnitt-titel">Zwei Wege zur Bezahlung</h2>
                <p class="abschnitt-untertitel">
                    Ob über die Pflegekasse oder als Selbstzahler – beide Wege sind möglich.
                </p>
            </div>

            <div class="wege-raster">

                <!-- Weg 1: Pflegekasse -->
                <div class="weg-karte weg-karte--primaer">
                    <h3>Über die Pflegekasse</h3>
                    <p class="weg-highlight">Bis zu <strong>125 € monatlich</strong> übernimmt die Kasse</p>
                    <p>
                        Wenn Sie einen <strong>anerkannten Pflegegrad (1–5)</strong> haben,
                        steht Ihnen der sogenannte Entlastungsbetrag zu –
                        und den können Sie für meine Leistungen nutzen.
                    </p>
                    <ul class="weg-liste">
                        <li>✓ Für alle Pflegegrade (1–5)</li>
                        <li>✓ Monatlich bis zu 125 €</li>
                        <li>✓ Nicht verbrauchte Beträge können übertragen werden</li>
                        <li>✓ Ich helfe Ihnen bei der Abrechnung</li>
                    </ul>
                    <a href="#paragraf" class="btn btn-outline" style="margin-top:1.5rem;">
                        Was ist §45b SGB XI?
                    </a>
                </div>

                <!-- Weg 2: Selbstzahler -->
                <div class="weg-karte">
                    <h3>Als Selbstzahler</h3>
                    <p class="weg-highlight">Herzlich willkommen – ganz ohne Pflegegrad</p>
                    <p>
                        Sie brauchen keinen Pflegegrad, um meine Unterstützung in Anspruch zu nehmen.
                        Auch als Selbstzahler sind Sie bei mir herzlich willkommen.
                    </p>
                    <ul class="weg-liste">
                        <li>✓ Kein Pflegegrad nötig</li>
                        <li>✓ Flexible Einsatzzeiten</li>
                        <li>✓ Transparente Stundensätze</li>
                        <li>✓ Individuelle Vereinbarung</li>
                    </ul>
                    <a href="https://nils-digital.de/pages/kontakt.html" target="_blank" rel="noopener" class="btn btn-primaer" style="margin-top:1.5rem;">
                        Angebot anfragen
                    </a>
                </div>

            </div>
        </div>
    </section>


    <!-- ===== §45b ERKLÄRT ===== -->
    <section class="paragraf-erklaerung" id="paragraf" aria-labelledby="paragraf-titel">
        <div class="container">

            <div class="abschnitt-kopf">
                <h2 id="paragraf-titel" class="abschnitt-titel">
                    Was ist der Entlastungsbetrag §45b SGB XI?
                </h2>
                <p class="abschnitt-untertitel">
                    Einfach erklärt – damit Sie wissen, was Ihnen zusteht.
                </p>
            </div>

            <div class="paragraf-inhalt">

                <div class="paragraf-fakten">
                    <h3>Auf einen Blick</h3>
                    <dl class="fakten-liste">

                        <div class="fakten-karte">
                            <dt>Betrag</dt>
                            <dd>Bis zu <strong>125 €</strong> pro Monat</dd>
                        </div>

                        <div class="fakten-karte">
                            <dt>Voraussetzung</dt>
                            <dd>Pflegegrad <strong>1–5</strong></dd>
                        </div>

                        <div class="fakten-karte">
                            <dt>Zweck</dt>
                            <dd>Alltagsbegleitung & Betreuung</dd>
                        </div>

                        <div class="fakten-karte">
                            <dt>Übertragbar</dt>
                            <dd>Bis <strong>30. Juni</strong> des Folgejahres</dd>
                        </div>

                        <div class="fakten-karte">
                            <dt>Anbieter</dt>
                            <dd>Anerkannte Betreuungsperson</dd>
                        </div>

                    </dl>
                </div>

                <div class="paragraf-text">
                    <p>
                        Der <strong>Entlastungsbetrag nach §45b SGB XI</strong> ist eine Leistung der
                        gesetzlichen Pflegekassen. Jede Person mit einem anerkannten Pflegegrad
                        (1 bis 5) erhält monatlich bis zu <strong>125 Euro</strong>, die für
                        unterstützende Dienstleistungen eingesetzt werden können.
                    </p>
                    <p>
                        Diese Leistung ist <strong>zweckgebunden</strong> – sie kann also nur für
                        bestimmte Dienste wie Alltagsbegleitung, Betreuung oder hauswirtschaftliche
                        Hilfe genutzt werden. Meine Leistungen fallen genau in diesen Bereich.
                    </p>
                    <p>
                        Nicht verbrauchte Beträge können im <strong>gleichen Kalenderjahr</strong> und
                        bis zum 30. Juni des Folgejahres nachträglich eingelöst werden –
                        das Geld verfällt also nicht sofort.
                    </p>
                    <p>
                        <strong>Gut zu wissen:</strong> Dieser Betrag kommt <em>zusätzlich</em> zu
                        anderen Pflegeleistungen und verringert diese nicht.
                    </p>
                </div>

            </div>

        </div>
    </section>


    <!-- ===== SO LÄUFT ES AB ===== -->
    <section class="ablauf" aria-labelledby="ablauf-titel">
        <div class="container">

            <div class="abschnitt-kopf">
                <h2 id="ablauf-titel" class="abschnitt-titel">So einfach geht es</h2>
                <p class="abschnitt-untertitel">In wenigen Schritten zur Unterstützung.</p>
            </div>

            <ol class="ablauf-schritte">

                <li class="ablauf-schritt">
                    <div class="schritt-nummer" aria-hidden="true">1</div>
                    <div class="schritt-text">
                        <h3>Kontakt aufnehmen</h3>
                        <p>
                            Rufen Sie mich an oder schreiben Sie mir. Wir sprechen in Ruhe über
                            Ihre Situation und Ihre Wünsche.
                        </p>
                    </div>
                </li>

                <li class="ablauf-schritt">
                    <div class="schritt-nummer" aria-hidden="true">2</div>
                    <div class="schritt-text">
                        <h3>Pflegekasse anfragen</h3>
                        <p>
                            Falls Sie über die Pflegekasse abrechnen möchten: Ich begleite Sie
                            dabei, den Antrag für den Entlastungsbetrag zu stellen.
                        </p>
                    </div>
                </li>

                <li class="ablauf-schritt">
                    <div class="schritt-nummer" aria-hidden="true">3</div>
                    <div class="schritt-text">
                        <h3>Persönliches Erstgespräch</h3>
                        <p>
                            Bei Ihnen zu Hause, ganz unverbindlich – wir klären alles
                            persönlich und finden die passende Lösung.
                        </p>
                    </div>
                </li>

                <li class="ablauf-schritt">
                    <div class="schritt-nummer" aria-hidden="true">4</div>
                    <div class="schritt-text">
                        <h3>Loslegen!</h3>
                        <p>
                            Wir vereinbaren regelmäßige Termine – und ich bin für Sie da.
                            Unkompliziert, verlässlich, herzlich.
                        </p>
                    </div>
                </li>

            </ol>

        </div>
    </section>


    <!-- ===== KONTAKT-CTA ===== -->
    <section class="kontakt-cta" aria-labelledby="abrechnung-cta-titel">
        <div class="container kontakt-cta-inner">
            <h2 id="abrechnung-cta-titel">Fragen zur Abrechnung? Ich helfe gerne.</h2>
            <p>
                Die Pflegekasse und ihre Regelungen können kompliziert sein –
                aber ich kenne mich aus und erkläre Ihnen alles in Ruhe.
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
