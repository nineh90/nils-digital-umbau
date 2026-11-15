<?php

namespace App\Support;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Block\Paragraph;

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
 *
 * Bilder und Videos im Text (NID-49): ein Absatz, der nur aus
 * ![Beschreibung](adresse "Unterschrift") besteht, wird zur Figur – siehe
 * MedienRenderer. Das eigene HTML entsteht dort und bleibt damit unabhängig
 * von html_input: maskiert wird weiterhin alles, was jemand von Hand eintippt.
 */
class Markdown
{
    private MarkdownConverter $converter;

    public function __construct()
    {
        $umgebung = new Environment([
            'renderer' => [
                'soft_break' => "<br />\n",
            ],
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);

        $umgebung->addExtension(new CommonMarkCoreExtension);
        // Vorrang vor dem Standard-Absatz; gibt null zurück, wenn kein Medium drinsteht.
        $umgebung->addRenderer(Paragraph::class, new MedienRenderer, 10);

        $this->converter = new MarkdownConverter($umgebung);
    }

    /**
     * Alle Adressen von Bildern und Videos im Text, in der Reihenfolge ihres
     * Auftretens. Für die Prüfung beim Ausgeben der Inhalte.
     *
     * @return list<string>
     */
    public function medienAdressen(?string $markdown): array
    {
        preg_match_all('/!\[[^\]]*\]\(\s*<?([^\s)>]+)>?/', (string) $markdown, $treffer);

        return $treffer[1];
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
