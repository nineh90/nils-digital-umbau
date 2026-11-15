<?php

namespace App\Support;

use Illuminate\Support\Facades\Blade;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\RegexHelper;

/**
 * Macht aus einem Absatz, der nur aus einem Bild besteht, eine eigene Figur.
 *
 * CommonMark setzt jedes Bild in einen Absatz, auch wenn es allein auf seiner
 * Zeile steht. Ein <figure> darf aber nicht in einem <p> stehen, und ein Video
 * oder eine Einbettung erst recht nicht. Deshalb greift dieser Renderer schon
 * beim Absatz: steht darin nur ein Bild, entsteht statt des Absatzes eine
 * Figur samt Bildunterschrift. Alles andere – auch ein Bild mitten im Satz –
 * geht unverändert an den Standard-Renderer zurück (Rückgabe null).
 */
final class MedienRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): ?string
    {
        if (! $node instanceof Paragraph || ! ($bild = $this->einzigesBild($node))) {
            return null;
        }

        // javascript: und Verwandte gehen an den Standard-Renderer zurück,
        // der sie wegen allow_unsafe_links entschärft.
        if (RegexHelper::isLinkPotentiallyUnsafe($bild->getUrl())) {
            return null;
        }

        $medium = Medium::aus($bild->getUrl());
        $beschreibung = $this->beschreibung($bild);
        $unterschrift = (string) $bild->getTitle();

        $inhalt = match ($medium->art) {
            Medium::YOUTUBE => $this->youtube($medium, $beschreibung),
            Medium::VIDEO => $this->video($medium, $beschreibung),
            default => sprintf(
                '<img src="%s" alt="%s" loading="lazy" decoding="async">',
                e($medium->quelle),
                e($beschreibung),
            ),
        };

        $klasse = 'medium medium--'.$medium->art.($medium->hochkant ? ' medium--hochkant' : '');

        return sprintf(
            '<figure class="%s">%s%s</figure>',
            $klasse,
            $inhalt,
            $unterschrift !== '' ? '<figcaption>'.e($unterschrift).'</figcaption>' : '',
        );
    }

    /**
     * Das Bild, wenn es der einzige Inhalt des Absatzes ist.
     * Leerraum und Zeilenumbrüche drumherum zählen nicht.
     */
    private function einzigesBild(Paragraph $absatz): ?Image
    {
        $bild = null;

        foreach ($absatz->children() as $kind) {
            if ($kind instanceof Newline || ($kind instanceof Text && trim($kind->getLiteral()) === '')) {
                continue;
            }

            if (! $kind instanceof Image || $bild !== null) {
                return null;
            }

            $bild = $kind;
        }

        return $bild;
    }

    /** Der Text in den eckigen Klammern, also die Bildbeschreibung. */
    private function beschreibung(Image $bild): string
    {
        $text = '';

        foreach ($bild->iterator() as $kind) {
            if ($kind instanceof Text) {
                $text .= $kind->getLiteral();
            }
        }

        return trim($text);
    }

    /**
     * Eigene Videodatei. Das Seitenverhältnis bringt die Datei selbst mit –
     * hochkant oder quer muss niemand angeben. preload="metadata" lädt nur
     * Länge und Format, nicht das ganze Video.
     */
    private function video(Medium $medium, string $beschreibung): string
    {
        return sprintf(
            '<video src="%s" controls playsinline preload="metadata"%s%s>'
            .'<a href="%s">Video herunterladen</a></video>',
            e($medium->quelle),
            ($vorschau = $this->vorschaubild($medium->quelle)) ? ' poster="'.e($vorschau).'"' : '',
            $beschreibung !== '' ? ' aria-label="'.e($beschreibung).'"' : '',
            e($medium->quelle),
        );
    }

    /**
     * Ein gleichnamiges JPG neben der Videodatei wird zum Vorschaubild.
     * Ohne zeigen manche Browser – Safari auf dem iPhone vorneweg – bis zum
     * Abspielen nur eine schwarze Fläche, weil sie trotz preload nichts laden.
     */
    private function vorschaubild(string $quelle): ?string
    {
        $pfad = (string) parse_url($quelle, PHP_URL_PATH);

        if (! str_starts_with($quelle, '/') || str_starts_with($quelle, '//')) {
            return null;
        }

        $bild = preg_replace('/\.[a-z0-9]+$/i', '.jpg', $pfad);

        return is_file(public_path(ltrim($bild, '/'))) ? $bild : null;
    }

    /** YouTube über die vorhandene Einbettung: erst auf Klick, sonst nur Vorschau. */
    private function youtube(Medium $medium, string $beschreibung): string
    {
        return Blade::render(
            '<x-einbettung :quelle="$quelle" :titel="$titel" anbieter="YouTube" :direktlink="$direktlink" :format="$format" />',
            [
                'quelle' => $medium->einbettungsAdresse(),
                'titel' => $beschreibung !== '' ? $beschreibung : 'Video',
                'direktlink' => $medium->direktAdresse(),
                'format' => $medium->hochkant ? 'hochkant' : 'quer',
            ],
        );
    }
}
