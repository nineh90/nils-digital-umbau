<?php
/**
 * Header-Include – wird auf allen Seiten eingebunden
 * Enthält: DOCTYPE, Meta-Tags, CSS-Links, Navigation
 */

// Aktuelle Seite ermitteln für aktive Navigation
$aktuelle_seite = basename($_SERVER['PHP_SELF']);

// Basispfad: lokal leer, auf Strato mit Unterordner-Pfad
$base = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'localhost') ? '' : '/demo/brackemeyer_alltagsbegleitung';
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO: Seitentitel und Beschreibung werden von der jeweiligen Seite gesetzt -->
    <title><?php echo isset($seiten_titel) ? htmlspecialchars($seiten_titel) . ' | Andrea Brackemeyer' : 'Andrea Brackemeyer – Alltagsbegleitung'; ?></title>
    <meta name="description" content="<?php echo isset($seiten_beschreibung) ? htmlspecialchars($seiten_beschreibung) : 'Andrea Brackemeyer – Alltagsbegleitung für Senioren mit Herz, Respekt und Verlässlichkeit. Über 20 Jahre Erfahrung. Jetzt Kontakt aufnehmen.'; ?>">

    <!-- Suchmaschinen-Angaben -->
    <!-- TODO [SEO-CHECK vor Deploy]: noindex entfernen → "index, follow" setzen -->
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="Andrea Brackemeyer">

    <!-- Open Graph für Social Media -->
    <meta property="og:title" content="<?php echo isset($seiten_titel) ? htmlspecialchars($seiten_titel) . ' | Andrea Brackemeyer' : 'Andrea Brackemeyer – Alltagsbegleitung'; ?>">
    <meta property="og:description" content="Alltagsbegleitung für Senioren – mit Herz, Respekt und Verlässlichkeit.">
    <meta property="og:type" content="website">

    <!-- Google Fonts: Gut lesbare Schrift für Senioren -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">

    <!-- Eigene Stylesheets -->
    <link rel="stylesheet" href="<?= $base ?>/css/style.css">
    <link rel="stylesheet" href="<?= $base ?>/css/responsive.css">

    <link rel="icon" type="image/png" href="<?= $base ?>/images/favicon.png">
</head>
<body>

<!-- ===== HEADER / NAVIGATION ===== -->
<header class="site-header">
    <div class="container header-inner">

        <!-- Logo -->
        <a href="<?= $base ?>/index.php" class="site-logo" aria-label="Andrea Brackemeyer – Startseite">
            <img
                src="<?= $base ?>/images/logo/brackemeyer_alltagsbegleitung_logo.png"
                alt="Andrea Brackemeyer – Alltagsbegleitung"
                class="site-logo-bild"
                width="220"
                height="60"
            >
        </a>

        <!-- Telefonnummer prominent im Header (wichtig für Senioren!) -->
        <a href="tel:015161657136" class="header-telefon" aria-label="Jetzt anrufen">
            0151 61 65 71 36
        </a>

        <!-- Hamburger-Button für Mobilgeräte -->
        <button class="nav-toggle" id="nav-toggle" aria-label="Navigation öffnen" aria-expanded="false" aria-controls="hauptnavigation">
            <span class="hamburger-linie"></span>
            <span class="hamburger-linie"></span>
            <span class="hamburger-linie"></span>
        </button>

        <!-- Dekoratives Herz – alle Seiten, nur Mobile/Tablet -->
        <div class="header-herz-deko" aria-hidden="true">
            <svg viewBox="0 0 200 185" xmlns="http://www.w3.org/2000/svg">
                <path class="header-herz-pfad" d="M100,170 C55,138 8,105 8,62 C8,28 30,8 58,8 C74,8 88,18 100,34 C112,18 126,8 142,8 C170,8 192,28 192,62 C192,105 145,138 100,170 Z"/>
            </svg>
        </div>

        <!-- Hauptnavigation -->
        <nav class="hauptnavigation" id="hauptnavigation" aria-label="Hauptnavigation">
            <ul class="nav-liste">
                <li>
                    <a href="<?= $base ?>/index.php" <?php echo ($aktuelle_seite === 'index.php') ? 'class="aktiv" aria-current="page"' : ''; ?>>
                        Startseite
                    </a>
                </li>
                <li>
                    <a href="<?= $base ?>/pages/about.php" <?php echo ($aktuelle_seite === 'about.php') ? 'class="aktiv" aria-current="page"' : ''; ?>>
                        Über mich
                    </a>
                </li>
                <li>
                    <a href="<?= $base ?>/pages/leistungen.php" <?php echo ($aktuelle_seite === 'leistungen.php') ? 'class="aktiv" aria-current="page"' : ''; ?>>
                        Leistungen
                    </a>
                </li>
                <li>
                    <a href="<?= $base ?>/pages/abrechnung.php" <?php echo ($aktuelle_seite === 'abrechnung.php') ? 'class="aktiv" aria-current="page"' : ''; ?>>
                        Abrechnung & Kosten
                    </a>
                </li>
                <li>
                    <a href="https://nils-digital.de/pages/kontakt.html" target="_blank" rel="noopener" class="nav-cta <?php echo ($aktuelle_seite === 'kontakt.php') ? 'aktiv' : ''; ?>" <?php echo ($aktuelle_seite === 'kontakt.php') ? 'aria-current="page"' : ''; ?>>
                        Kontakt
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</header>
<!-- ===== ENDE HEADER ===== -->
