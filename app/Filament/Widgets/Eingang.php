<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

/**
 * Was hereingekommen ist – und ob die Benachrichtigung dazu auch rausging.
 *
 * Die beiden hinteren Kacheln gibt es wegen eines stillen Fehlers: das
 * Kontaktformular sagt „Danke", sobald die Anfrage gespeichert und die Mail
 * in die Warteschlange gelegt ist. Ob sie danach ankommt, erfährt das
 * Formular nie. Steht der Arbeiter oder lehnt Google den Versand ab, merkt
 * es niemand – außer hier.
 *
 * Die Anfrage selbst geht dabei nicht verloren, sie steht unter „Anfragen".
 * Verloren geht nur, dass Nils davon erfährt und die anfragende Person eine
 * Bestätigung bekommt.
 */
class Eingang extends StatsOverviewWidget
{
    /**
     * Ab wann eine wartende Mail als hängengeblieben gilt. Der Arbeiter holt
     * alle drei Sekunden ab; zehn Minuten sind weit jenseits jeder normalen
     * Verzögerung und kurz genug, um am selben Tag aufzufallen.
     */
    private const HAENGT_NACH_MINUTEN = 10;

    protected static ?int $sort = 0;

    protected ?string $heading = 'Eingang';

    protected function getStats(): array
    {
        $neu = Inquiry::neu()->count();

        $haengen = DB::table('jobs')
            ->where('created_at', '<', now()->subMinutes(self::HAENGT_NACH_MINUTEN)->getTimestamp())
            ->count();

        $gescheitert = DB::table('failed_jobs')->count();

        return [
            Stat::make('Neue Anfragen', $neu)
                ->description($neu > 0 ? 'warten auf Antwort' : 'alles beantwortet')
                ->descriptionIcon($neu > 0 ? 'heroicon-m-envelope' : 'heroicon-m-check-circle')
                ->color($neu > 0 ? 'warning' : 'success')
                ->url(route('filament.admin.resources.inquiries.index')),

            Stat::make('Mails, die hängen', $haengen)
                ->description($haengen > 0
                    ? 'Liegen seit über '.self::HAENGT_NACH_MINUTEN.' Minuten in der Warteschlange – der Versand steht.'
                    : 'Warteschlange läuft')
                ->descriptionIcon($haengen > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($haengen > 0 ? 'danger' : 'success'),

            Stat::make('Gescheiterte Mails', $gescheitert)
                ->description($gescheitert > 0
                    ? 'Nach drei Versuchen aufgegeben. Die Anfragen stehen trotzdem unter „Anfragen".'
                    : 'keine')
                ->descriptionIcon($gescheitert > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($gescheitert > 0 ? 'danger' : 'success'),
        ];
    }
}
