<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Project;
use App\Models\Review;
use App\Models\Service;
use App\Models\TeamMember;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Was auf der Seite steht, in Zahlen.
 *
 * Jede Kachel führt auf ihre Liste – vom Überblick aus ist der nächste
 * Handgriff meistens „nachsehen", nicht „lesen".
 */
class Kennzahlen extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Inhalte';

    protected function getStats(): array
    {
        $entwuerfe = Post::where('status', 'draft')->count();
        $stimmen = Review::visible()->get();

        return [
            Stat::make('Beiträge', Post::published()->count())
                ->description($entwuerfe > 0
                    ? "{$entwuerfe} noch im Entwurf"
                    : 'alle veröffentlicht')
                ->descriptionColor($entwuerfe > 0 ? 'warning' : 'gray')
                ->icon('heroicon-o-document-text')
                ->url(route('filament.admin.resources.posts.index')),

            Stat::make('Projekte', Project::count())
                ->description(Project::featured()->count() . ' auf der Startseite')
                ->icon('heroicon-o-rectangle-stack')
                ->url(route('filament.admin.resources.projects.index')),

            /*
             * Der Schnitt hier muss über alle sichtbaren Stimmen laufen, auch
             * die ohne Text – genau wie der Schema.org-Block auf der
             * Startseite. Sonst stehen zwei verschiedene Zahlen an zwei Stellen
             * für dasselbe.
             */
            Stat::make('Kundenstimmen', $stimmen->count())
                ->description($stimmen->isNotEmpty()
                    ? number_format($stimmen->avg('rating'), 1, ',', '') . ' von 5 im Schnitt'
                    : 'noch keine')
                ->icon('heroicon-o-star')
                ->url(route('filament.admin.resources.reviews.index')),

            Stat::make('Leistungen', Service::count())
                ->description(TeamMember::visible()->count() . ' Personen im Team')
                ->icon('heroicon-o-briefcase')
                ->url(route('filament.admin.resources.services.index')),
        ];
    }
}
