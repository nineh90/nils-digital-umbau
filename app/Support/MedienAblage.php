<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Legt Bilder und Videos aus dem Beitragseditor ab (NID-49).
 *
 * Die Bildfelder verkleinern im Browser (FilePond, siehe AppServiceProvider).
 * Der Upload-Knopf im Markdown-Editor kann das nicht – ohne diese Klasse
 * landete jedes Handyfoto in voller Größe im Repo und in jedem Seitenaufruf.
 *
 * Videos bleiben unangetastet: sie neu zu kodieren bräuchte ffmpeg auf jeder
 * Maschine, und ein Handyvideo ist bereits für das Netz komprimiert.
 *
 * Die Dateinamen bleiben lesbar, weil die Dateien über das Repo reisen und
 * dort im Diff auftauchen: „kein-einzelfall-startseite-3f9a.jpg“ sagt mehr als
 * eine zufällige Zeichenkette.
 */
final class MedienAblage
{
    public const KANTE = 1600;

    public static function speichern(UploadedFile $datei, string $verzeichnis, string $disk = 'inhalte'): string
    {
        $endung = strtolower($datei->getClientOriginalExtension() ?: $datei->extension());
        $name = Str::slug(pathinfo($datei->getClientOriginalName(), PATHINFO_FILENAME), '-', 'de') ?: 'medium';
        $pfad = trim($verzeichnis, '/').'/'.Str::limit($name, 60, '').'-'.Str::lower(Str::random(4)).'.'.$endung;

        $inhalt = self::verkleinert($datei->getRealPath(), $endung) ?? file_get_contents($datei->getRealPath());

        Storage::disk($disk)->put($pfad, $inhalt, 'public');

        return $pfad;
    }

    /**
     * Das Bild mit längster Kante KANTE, oder null, wenn nichts zu tun ist.
     * GIFs bleiben, wie sie sind – GD würde eine Animation auf das erste Bild
     * reduzieren.
     */
    private static function verkleinert(string $quelle, string $endung): ?string
    {
        if (! extension_loaded('gd') || ! in_array($endung, ['jpg', 'jpeg', 'png', 'webp'], true)) {
            return null;
        }

        $bild = @imagecreatefromstring((string) file_get_contents($quelle));

        if (! $bild) {
            return null;
        }

        $bild = self::aufgerichtet($bild, $quelle, $endung);
        $breite = imagesx($bild);
        $hoehe = imagesy($bild);
        $faktor = min(1, self::KANTE / max($breite, $hoehe));

        if ($faktor < 1) {
            // imagecopyresampled statt imagescale: dessen Bicubic-Modus fehlt
            // in manchen GD-Builds und liefert dann stillschweigend false.
            $ziel = imagecreatetruecolor((int) round($breite * $faktor), (int) round($hoehe * $faktor));
            imagealphablending($ziel, false);
            imagesavealpha($ziel, true);
            imagecopyresampled($ziel, $bild, 0, 0, 0, 0, imagesx($ziel), imagesy($ziel), $breite, $hoehe);
            $bild = $ziel;
        } elseif (! in_array($endung, ['jpg', 'jpeg'], true)) {
            // Klein genug und nicht gedreht: Original behalten, auch wegen
            // der Transparenz in PNG und WebP.
            return null;
        }

        ob_start();
        match ($endung) {
            'png' => (imagesavealpha($bild, true) && imagepng($bild, null, 8)),
            'webp' => imagewebp($bild, null, 82),
            default => imagejpeg($bild, null, 82),
        };

        return (string) ob_get_clean();
    }

    /**
     * Handys speichern hochkant aufgenommene Fotos oft quer und vermerken die
     * Drehung nur in den EXIF-Daten. GD liest diese nicht – ohne diesen Schritt
     * läge das Foto nach dem Verkleinern auf der Seite.
     */
    private static function aufgerichtet(\GdImage $bild, string $quelle, string $endung): \GdImage
    {
        if (! in_array($endung, ['jpg', 'jpeg'], true) || ! function_exists('exif_read_data')) {
            return $bild;
        }

        $lage = (int) (@exif_read_data($quelle)['Orientation'] ?? 1);

        return match ($lage) {
            3 => imagerotate($bild, 180, 0),
            6 => imagerotate($bild, -90, 0),
            8 => imagerotate($bild, 90, 0),
            default => $bild,
        };
    }
}
