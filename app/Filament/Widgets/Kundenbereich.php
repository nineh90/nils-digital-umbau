<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

/**
 * Einstieg in den Kundenbereich.
 *
 * Das Ticketsystem ist eine eigene Anwendung auf intern.nils-digital.de und
 * teilt mit dieser Seite weder Datenbank noch Sitzung. Neuigkeiten von dort
 * hier anzuzeigen, hiesse also: eine Schnittstelle drüben bauen, einen Zugang
 * dafür verwalten und die Antwort zwischenspeichern, damit das Dashboard nicht
 * bei jedem Aufruf auf einen fremden Server wartet.
 *
 * Das ist ein eigenes Vorhaben und keine halbe Stunde. Bis dahin steht hier
 * der Weg dorthin – ein Kasten, der behauptet, es kämen gleich Zahlen, wäre
 * schlechter als einer, der ehrlich verlinkt.
 */
class Kundenbereich extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.kundenbereich';

    protected int | string | array $columnSpan = 'full';
}
