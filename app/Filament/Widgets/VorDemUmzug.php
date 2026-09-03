<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Project;
use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Was noch offen ist.
 *
 * Zählt bewusst nicht, was da ist, sondern was fehlt – und zwar nur Dinge, die
 * sich in der Redaktion selbst erledigen lassen. Steht überall eine Null, ist
 * hier nichts zu tun; das Feld färbt sich dann grün und die Kacheln
 * verschwinden nicht, damit man den Unterschied zwischen „erledigt" und
 * „wird nicht geprüft" sieht.
 */
class VorDemUmzug extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Offen';

    protected ?string $description = 'Punkte, die sich hier erledigen lassen. Der Domain-Umzug hängt an keinem davon – aber die Seite wirkt damit fertiger.';

    protected function getStats(): array
    {
        /*
         * Ohne Fallstudie bekommt ein Projekt keine eigene Unterseite und
         * steht auf noindex. Von allen Punkten hier ist das der grösste Hebel
         * für die Sichtbarkeit – deshalb steht er vorn.
         */
        $ohneFallstudie = Project::whereNull('body')->orWhere('body', '')->count();
        $entwuerfe = Post::where('status', 'draft')->count();
        $unsichtbar = Review::where('is_visible', false)->count();

        return [
            $this->kachel(
                'Projekte ohne Fallstudie',
                $ohneFallstudie,
                'Ihre Detailseiten stehen auf noindex und werden von Google übergangen.',
                'alle haben eine',
                route('filament.admin.resources.projects.index'),
            ),

            $this->kachel(
                'Beiträge im Entwurf',
                $entwuerfe,
                'Stehen fertig da, sind aber nicht veröffentlicht.',
                'nichts liegen geblieben',
                route('filament.admin.resources.posts.index'),
            ),

            $this->kachel(
                'Ausgeblendete Stimmen',
                $unsichtbar,
                'Zählen weder für den Schnitt noch als Zitat.',
                'alle sichtbar',
                route('filament.admin.resources.reviews.index'),
            ),
        ];
    }

    private function kachel(string $titel, int $anzahl, string $wennOffen, string $wennErledigt, string $ziel): Stat
    {
        return Stat::make($titel, $anzahl)
            ->description($anzahl > 0 ? $wennOffen : $wennErledigt)
            ->descriptionIcon($anzahl > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
            ->color($anzahl > 0 ? 'warning' : 'success')
            ->url($ziel);
    }
}
