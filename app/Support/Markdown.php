<?php

namespace App\Support;

use League\CommonMark\CommonMarkConverter;

/**
 * Wandelt den Fließtext der Beiträge in HTML.
 *
 * Ablösung für parseContent() aus der alten legacy/js/post.js. Der dortige
 * Parser kannte genau sechs Regeln – ## , ### , **fett**, *kursiv*,
 * "- Listenpunkt" und Leerzeile als Absatz – und keine Link-Syntax. Deshalb
 * mussten Links separat in links[] gepflegt werden.
 *
 * Diese sechs Regeln sind eine echte Markdown-Teilmenge, der Altbestand läuft
 * also unverändert durch. Zwei Einstellungen sind dabei wesentlich:
 *
 * soft_break: Der alte Parser machte aus einem einfachen Zeilenumbruch ein
 * <br>. CommonMark behandelt ihn standardmäßig als Leerzeichen und würde
 * Zeilen zusammenziehen – in 14 Beiträgen wäre die Absatzgliederung dahin.
 *
 * html_input: Der alte Weg über innerHTML hätte eingebettetes HTML ausgeführt.
 * Hier wird es maskiert. Der Altbestand enthält keins, und für neue Beiträge
 * ist es die sicherere Vorgabe.
 */
class Markdown
{
    private CommonMarkConverter $converter;

    public function __construct()
    {
        $this->converter = new CommonMarkConverter([
            'renderer' => [
                'soft_break' => "<br />\n",
            ],
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
    }

    public function toHtml(?string $markdown): string
    {
        if (blank($markdown)) {
            return '';
        }

        return (string) $this->converter->convert($markdown);
    }

    /**
     * Lesezeit in Minuten, 200 Wörter je Minute.
     * Bewusst identisch zur alten Berechnung, damit sich die Angabe unter
     * bestehenden Beiträgen durch den Umbau nicht verschiebt.
     */
    public function readingMinutes(string ...$texte): int
    {
        $woerter = preg_split('/\s+/', trim(implode(' ', $texte)), -1, PREG_SPLIT_NO_EMPTY);

        return max(1, (int) ceil(count($woerter) / 200));
    }
}
