<?php
/**
 * Kontakt – kontakt.php
 * TODO: Formular-Verarbeitung noch einbauen
 */

$seiten_titel = 'Kontakt';
$seiten_beschreibung = 'Kontaktieren Sie Andrea Brackemeyer – Alltagsbegleitung für Senioren. Rufen Sie an oder schreiben Sie eine Nachricht. Ich melde mich schnell zurück.';

include '../includes/header.php';
?>

<main id="hauptinhalt">

    <!-- ===== SEITENHEADER ===== -->
    <section class="seiten-hero" aria-label="Seitenüberschrift">
        <div class="container">
            <p class="seiten-hero-vortitel">Ich freue mich von Ihnen zu hören</p>
            <h1 class="seiten-hero-titel">Kontakt</h1>
            <p class="seiten-hero-untertitel">
                Haben Sie Fragen oder möchten Sie einen Termin vereinbaren?
                Ich bin für Sie da – im Raum Tecklenburg, Lengerich und dem nördlichen Münsterland.
            </p>
        </div>
    </section>


    <!-- ===== KONTAKT-HAUPTBEREICH ===== -->
    <section class="kontakt-bereich" aria-labelledby="kontakt-bereich-titel">
        <div class="container kontakt-bereich-inner">

            <!-- ===== KONTAKTINFORMATIONEN ===== -->
            <div class="kontakt-info">
                <h2 id="kontakt-bereich-titel" class="abschnitt-titel" style="text-align:left;">
                    So erreichen Sie mich
                </h2>

                <!-- Direkter Anruf – prominenteste Option -->
                <div class="kontakt-karte kontakt-karte--hervorgehoben">
                    <div>
                        <h3>Am liebsten: Anrufen</h3>
                        <p>Oft klärt sich vieles am schnellsten im persönlichen Gespräch.</p>
                        <a href="tel:015161657136" class="kontakt-telefon-link">
                            0151 61 65 71 36
                        </a>
                        <p class="kontakt-hinweis">Sollte ich gerade nicht rangehen, rufe ich zurück.</p>
                    </div>
                </div>

                <!-- Formular-Hinweis -->
                <div class="kontakt-karte">
                    <div>
                        <h3>Oder: Nachricht schreiben</h3>
                        <p>
                            Füllen Sie das Formular rechts aus – ich melde mich
                            schnellstmöglich bei Ihnen.
                        </p>
                    </div>
                </div>

                <!-- Persönliches Gespräch -->
                <div class="kontakt-karte">
                    <div>
                        <h3>Erstgespräch bei Ihnen zu Hause</h3>
                        <p>
                            Das erste Kennenlernen findet immer bei Ihnen zu Hause statt –
                            in Tecklenburg, Lengerich, Lienen, Ladbergen
                            und der gesamten Umgebung. Ganz unverbindlich und kostenlos.
                        </p>
                    </div>
                </div>

                <!-- Vertrauenshinweis -->
                <div class="kontakt-datenschutz-hinweis">
                    Ihre Daten werden vertraulich behandelt und nur zur Beantwortung
                    Ihrer Anfrage verwendet.
                    <a href="<?= $base ?>/pages/datenschutz.php">Datenschutzhinweis</a>
                </div>

            </div>


            <!-- ===== KONTAKTFORMULAR ===== -->
            <div class="kontakt-formular-wrapper">
                <h2 class="abschnitt-titel" style="text-align:left; margin-bottom:1.5rem;">
                    Nachricht schreiben
                </h2>

                <!-- TODO [vor Deploy]: Formular-Backend einrichten (kein Formspree – eigene Lösung) und fieldset-disabled entfernen -->
                <!-- Demo-Hinweis: Formular ist bis zum offiziellen Launch deaktiviert -->
                <div class="formular-demo-hinweis" role="note" style="background:#FBF6F0; border-left:4px solid #7A4B28; padding:1rem 1.25rem; border-radius:0 6px 6px 0; margin-bottom:1.5rem; font-size:1rem;">
                    <strong>Demo-Version</strong> – Das Formular wird vor dem offiziellen Launch aktiviert.<br>
                    Rufen Sie mich gerne direkt an: <a href="tel:015161657136" style="color:#7A4B28; font-weight:700;">0151 61 65 71 36</a>
                </div>
                <form
                    class="kontakt-formular"
                    action="#"
                    method="POST"
                    novalidate
                    id="kontakt-formular"
                    aria-label="Kontaktformular"
                >

                    <!-- Erfolgshinweis (wird per JS/PHP nach Absenden angezeigt) -->
                    <?php if (isset($_GET['gesendet']) && $_GET['gesendet'] == '1'): ?>
                    <div class="formular-erfolg" role="alert">
                        <strong>Vielen Dank für Ihre Nachricht!</strong><br>
                        Ich melde mich so bald wie möglich bei Ihnen.
                    </div>
                    <?php endif; ?>

                    <!-- Felder (fieldset disabled = Demo-Sperre bis Launch) -->
                    <fieldset disabled style="border:none; padding:0; margin:0; opacity:0.55;">
                    <div class="formular-gruppe">
                        <label for="name" class="formular-label">
                            Ihr Name <span class="pflichtfeld" aria-label="Pflichtfeld">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="formular-eingabe"
                            placeholder="Vorname und Nachname"
                            required
                            autocomplete="name"
                            aria-required="true"
                        >
                    </div>

                    <div class="formular-gruppe">
                        <label for="telefon" class="formular-label">
                            Telefonnummer
                        </label>
                        <input
                            type="tel"
                            id="telefon"
                            name="telefon"
                            class="formular-eingabe"
                            placeholder="z. B. 0151 12345678"
                            autocomplete="tel"
                        >
                        <p class="formular-hinweis">Wenn Sie lieber angerufen werden möchten.</p>
                    </div>

                    <div class="formular-gruppe">
                        <label for="email" class="formular-label">
                            E-Mail-Adresse <span class="pflichtfeld" aria-label="Pflichtfeld">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="formular-eingabe"
                            placeholder="ihre@email.de"
                            required
                            autocomplete="email"
                            aria-required="true"
                        >
                    </div>

                    <div class="formular-gruppe">
                        <label for="betreff" class="formular-label">
                            Worum geht es? <span class="pflichtfeld" aria-label="Pflichtfeld">*</span>
                        </label>
                        <select
                            id="betreff"
                            name="betreff"
                            class="formular-eingabe"
                            required
                            aria-required="true"
                        >
                            <option value="" disabled selected>Bitte auswählen …</option>
                            <option value="Erstgespräch vereinbaren">Erstgespräch vereinbaren</option>
                            <option value="Frage zu Leistungen">Frage zu Leistungen</option>
                            <option value="Frage zur Abrechnung / Pflegekasse">Frage zur Abrechnung / Pflegekasse</option>
                            <option value="Allgemeine Anfrage">Allgemeine Anfrage</option>
                            <option value="Sonstiges">Sonstiges</option>
                        </select>
                    </div>

                    <div class="formular-gruppe">
                        <label for="nachricht" class="formular-label">
                            Ihre Nachricht <span class="pflichtfeld" aria-label="Pflichtfeld">*</span>
                        </label>
                        <textarea
                            id="nachricht"
                            name="nachricht"
                            class="formular-eingabe formular-textarea"
                            rows="5"
                            placeholder="Schreiben Sie mir gerne, was Sie beschäftigt …"
                            required
                            aria-required="true"
                        ></textarea>
                    </div>

                    <!-- Datenschutz-Zustimmung -->
                    <div class="formular-gruppe formular-gruppe--checkbox">
                        <label class="checkbox-label">
                            <input
                                type="checkbox"
                                name="datenschutz"
                                id="datenschutz"
                                required
                                aria-required="true"
                                class="checkbox-eingabe"
                            >
                            <span class="checkbox-text">
                                Ich habe die
                                <a href="<?= $base ?>/pages/datenschutz.php" target="_blank">Datenschutzerklärung</a>
                                gelesen und bin damit einverstanden, dass meine Daten zur
                                Beantwortung meiner Anfrage verwendet werden.
                                <span class="pflichtfeld" aria-label="Pflichtfeld">*</span>
                            </span>
                        </label>
                    </div>

                    <div class="formular-gruppe">
                        <button type="submit" class="btn btn-primaer btn-gross formular-absenden">
                            Nachricht absenden
                        </button>
                        <p class="pflichtfeld-hinweis">
                            <span class="pflichtfeld">*</span> Pflichtfelder
                        </p>
                    </div>
                    </fieldset>

                </form>
            </div>

        </div>
    </section>

</main>

<?php include '../includes/logo_sprueche.php'; ?>

<?php include '../includes/footer.php'; ?>
