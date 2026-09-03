<?php

namespace App\Support;

/**
 * Die Strichzeichnungen der Seite.
 *
 * Stand früher als Array in components/symbol.blade.php. Sobald die Redaktion
 * ein Symbol auswählen können soll, braucht auch Filament die Namen – und zwei
 * Listen, die dasselbe meinen, laufen zuverlässig auseinander. Deshalb hier
 * die eine Quelle: das Blade holt sich den Pfad, das Formular die Namen.
 *
 * Keine Icon-Bibliothek: gut zwei Dutzend Pfade rechtfertigen keine weitere
 * Abhängigkeit im Bau. Neue Symbole kommen einfach in die Liste.
 */
class Symbole
{
    /** @return array<string, string> Name auf SVG-Pfad. */
    public static function alle(): array
    {
        return [
        // Technik und Entwicklung
        'chip'         => '<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 2v2M15 2v2M9 20v2M15 20v2M2 9h2M2 15h2M20 9h2M20 15h2"/>',
        'code'         => '<path d="m16 18 6-6-6-6M8 6l-6 6 6 6"/>',
        'globus'       => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
        'bildschirm'   => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
        'handy'        => '<rect x="5" y="2" width="14" height="20" rx="2"/><path d="M12 18h.01"/>',

        // Idee, Ziel, Wirkung
        'gluehbirne'   => '<path d="M15 14c.2-1 .7-1.7 1.5-2.5A5.6 5.6 0 0 0 18 8 6 6 0 0 0 6 8c0 1.2.5 2.5 1.5 3.5.8.8 1.3 1.5 1.5 2.5"/><path d="M9 18h6M10 22h4"/>',
        'ziel'         => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/>',
        'blitz'        => '<path d="M13 2 3 14h8l-1 8 10-12h-8l1-8z"/>',
        'funken'       => '<path d="m12 3 1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9L12 3z"/><path d="M5 20v2M4 21h2M18 3v2M17 4h2"/>',
        'rakete'       => '<path d="M4.5 16.5c-1.5 1.3-2 5-2 5s3.7-.5 5-2a2.1 2.1 0 0 0-3-3z"/><path d="m12 15-3-3a22 22 0 0 1 2-4A12.9 12.9 0 0 1 22 2c0 2.7-.8 7.5-6 11a22 22 0 0 1-4 2z"/><path d="M9 12H4s.6-3 2-4c1.6-1.1 5 0 5 0"/><path d="M12 15v5s3-.6 4-2c1.1-1.6 0-5 0-5"/>',

        // Handwerk und Betrieb
        'zahnrad'      => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.2 2.2M16.9 16.9l2.2 2.2M19.1 4.9l-2.2 2.2M7.1 16.9l-2.2 2.2"/>',
        'werkzeug'     => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-8 7.9l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 8-7.9l-3.8 3.8z"/>',
        'schluessel'   => '<circle cx="7.5" cy="15.5" r="4.5"/><path d="m10.7 12.3 8.8-8.8M17 6l2.5 2.5M14.5 8.5 17 11"/>',
        'gebaeude'     => '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01M16 6h.01M8 10h.01M16 10h.01M8 14h.01M16 14h.01"/>',
        'einkauf'      => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',

        // Inhalte und Ablage
        'dokument'     => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h5"/>',
        'ordner'       => '<path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.7-.9l-.8-1.2A2 2 0 0 0 7.9 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2z"/>',
        'stift'        => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
        'sprechblase'  => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',

        // Vertrauen und Ablauf
        'schloss'      => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'schild'       => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'uhr'          => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'haken'        => '<path d="M20 6 9 17l-5-5"/>',
        'personen'     => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.9"/>',
        'pfeil-rechts' => '<path d="M5 12h14M12 5l7 7-7 7"/>',
    ];;
    }

    /**
     * Unbekannter Name: lieber ein neutraler Punkt als eine leere Stelle oder
     * ein Fehler. So bleibt eine Kachel benutzbar, deren Symbol in der
     * Redaktion versehentlich falsch geschrieben wurde.
     */
    public static function pfad(?string $name): string
    {
        return self::alle()[$name] ?? '<circle cx="12" cy="12" r="9"/>';
    }

    /** Für Auswahlfelder in der Redaktion: Name auf Name. */
    public static function auswahl(): array
    {
        return array_combine(array_keys(self::alle()), array_keys(self::alle()));
    }
}
