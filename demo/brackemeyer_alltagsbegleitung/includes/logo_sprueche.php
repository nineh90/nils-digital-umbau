<?php
/**
 * logo_sprueche.php – Wiederverwendbare Sektion: Logo + animierte Mutmach-Sprüche
 * Einsatz: index.php (zwischen Leistungen und Vertrauensbereich), bei Bedarf auch auf anderen Seiten
 */
?>

<!-- ===== LOGO & SPRÜCHE-SEKTION ===== -->
<section class="logo-sprueche" aria-label="Motto und Leitsätze">
    <div class="container logo-sprueche-inner">

        <!-- Logo von Andrea -->
        <div class="logo-sprueche-logo-wrapper">
            <img
                src="<?= $base ?>/images/logo/brackemeyer_alltagsbegleitung_logo.png"
                alt="Andrea Brackemeyer – Alltagsbegleitung mit Herz"
                class="logo-sprueche-logo"
                width="320"
                height="100"
                loading="lazy"
            >
        </div>

        <!-- Dekorativer Trennstrich -->
        <div class="logo-sprueche-trenner" aria-hidden="true"></div>

        <!-- Animierte Sprüche – wechseln per CSS-Animation durch -->
        <div class="sprueche-container" aria-live="polite" aria-label="Leitsätze von Andrea Brackemeyer">

            <!-- Dekoratives Anführungszeichen -->
            <span class="sprueche-dekor" aria-hidden="true">&ldquo;</span>

            <div class="sprueche-stapel">

                <p class="spruch spruch--1">
                    Manchmal braucht man jemanden, der einfach zuhört –
                    <em>ich bin für Sie da.</em>
                </p>

                <p class="spruch spruch--2">
                    Zeit ist das wertvollste Geschenk.
                    <em>Ich nehme sie mir gerne für Sie.</em>
                </p>

                <p class="spruch spruch--3">
                    Jeder Mensch verdient echte Aufmerksamkeit –
                    <em>jeden Tag, mit ganzem Herzen.</em>
                </p>

                <p class="spruch spruch--4">
                    Gemeinsam ist der Alltag leichter.
                    <em>Ich freue mich, an Ihrer Seite zu sein.</em>
                </p>

                <p class="spruch spruch--5">
                    Verlässlichkeit ist keine Floskel –
                    <em>sie ist mein Versprechen an Sie.</em>
                </p>

            </div>

            <span class="sprueche-attribution">– Andrea Brackemeyer</span>
        </div>

    </div>
</section>
<!-- ===== ENDE LOGO & SPRÜCHE ===== -->
