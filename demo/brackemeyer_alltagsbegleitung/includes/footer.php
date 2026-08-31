<?php
/**
 * Footer-Include – wird auf allen Seiten eingebunden
 * Enthält: Kontaktinfos, Navigation, Copyright, JS-Einbindung
 */
?>

<!-- ===== FOOTER ===== -->
<footer class="site-footer">
    <div class="container footer-inner">

        <!-- Footer-Spalte 1: Über mich Kurzversion -->
        <div class="footer-spalte">
            <!-- Logo verlinkt zur Startseite -->
            <a href="<?= $base ?>/index.php" class="footer-logo-link" aria-label="Zur Startseite">
                <img src="<?= $base ?>/images/logo/brackemeyer_alltagsbegleitung_logo.png"
                     alt="Andrea Brackemeyer – Alltagsbegleitung Logo"
                     class="footer-logo">
            </a>
            <h3 class="footer-ueberschrift">Andrea Brackemeyer</h3>
            <p class="footer-tagline">Alltag mit Herz</p>
            <p>Ich nehme mir Zeit für Sie –<br>mit Herz, Respekt und Verlässlichkeit.</p>
            <p class="footer-kernwerte">
                <strong>ZEIT. NÄHE. UNTERSTÜTZUNG.</strong>
            </p>
            <p class="footer-einzugsgebiet">
                Tätig in der Region Tecklenburg –<br>
                Lengerich, Lienen, Ladbergen und Münsterland.
            </p>
        </div>

        <!-- Footer-Spalte 2: Schnellnavigation -->
        <div class="footer-spalte">
            <h3 class="footer-ueberschrift">Navigation</h3>
            <ul class="footer-nav">
                <li><a href="<?= $base ?>/index.php">Startseite</a></li>
                <li><a href="<?= $base ?>/pages/about.php">Über mich</a></li>
                <li><a href="<?= $base ?>/pages/leistungen.php">Leistungen</a></li>
                <li><a href="<?= $base ?>/pages/abrechnung.php">Abrechnung & Kosten</a></li>
                <li><a href="https://nils-digital.de/pages/kontakt.html" target="_blank" rel="noopener">Kontakt</a></li>
            </ul>
        </div>

        <!-- Footer-Spalte 3: Kontakt -->
        <div class="footer-spalte">
            <h3 class="footer-ueberschrift">Kontakt</h3>
            <address class="footer-kontakt">
                <p>
                    <a href="tel:015161657136" class="footer-telefon">
                        0151 61 65 71 36
                    </a>
                </p>
                <p>
                    <a href="https://nils-digital.de/pages/kontakt.html" target="_blank" rel="noopener" class="btn btn-outline btn-klein">
                        Nachricht schreiben
                    </a>
                </p>
            </address>
        </div>

    </div>

    <!-- Unterer Footer-Streifen -->
    <div class="footer-unten">
        <div class="container footer-unten-inner">
            <p class="copyright">
                &copy; <?php echo date('Y'); ?> Andrea Brackemeyer – Alltagsbegleitung. Alle Rechte vorbehalten.
            </p>
            <nav class="footer-rechtliches" aria-label="Rechtliche Links">
                <a href="<?= $base ?>/pages/impressum.php">Impressum</a>
                <a href="<?= $base ?>/pages/datenschutz.php">Datenschutz</a>
            </nav>
            <p class="footer-credit">
                Webdesign by <a href="https://www.nils-digital.de" target="_blank" rel="noopener">Nils-Digital</a>
            </p>
        </div>
    </div>
</footer>
<!-- ===== ENDE FOOTER ===== -->

<!-- JavaScript einbinden (am Ende für schnelleres Laden) -->
<script src="<?= $base ?>/js/main.js"></script>

</body>
</html>
