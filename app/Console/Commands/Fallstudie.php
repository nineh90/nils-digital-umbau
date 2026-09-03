<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Holt eine Fallstudie als Datei heraus und schreibt sie zurück.
 *
 * Der Markdown-Editor in Filament ist gut für ein paar Absätze. Eine Fallstudie
 * ist länger als das, und wer sie lieber im eigenen Editor schreibt, soll das
 * tun können, ohne den Umweg über Kopieren und Einfügen im Browser – dabei geht
 * regelmässig die Formatierung verloren.
 *
 * Die Datei liegt unter storage/app/fallstudien/ und ist von .gitignore erfasst:
 * die Wahrheit bleibt die Datenbank, die Datei ist nur der Schreibtisch.
 */
class Fallstudie extends Command
{
    protected $signature = 'nd:fallstudie
                            {projekt : Kennung des Projekts, z. B. lerndex}
                            {--zurueckschreiben : Datei in die Datenbank übernehmen statt sie herauszuholen}
                            {--datei= : Abweichender Pfad (Vorgabe: storage/app/fallstudien/<kennung>.md)}';

    protected $description = 'Holt die Fallstudie eines Projekts als Markdown-Datei heraus oder schreibt sie zurück';

    public function handle(): int
    {
        $projekt = Project::where('slug', $this->argument('projekt'))->first();

        if (! $projekt) {
            $this->error('Kein Projekt mit der Kennung "'.$this->argument('projekt').'".');
            $this->line('Vorhanden: '.Project::orderBy('position')->pluck('slug')->implode(', '));

            return self::FAILURE;
        }

        $datei = $this->option('datei') ?: storage_path('app/fallstudien/'.$projekt->slug.'.md');

        return $this->option('zurueckschreiben')
            ? $this->zurueck($projekt, $datei)
            : $this->heraus($projekt, $datei);
    }

    private function heraus(Project $projekt, string $datei): int
    {
        if (File::exists($datei) && ! $this->confirm("{$datei} gibt es schon. Mit dem Stand aus der Datenbank überschreiben?")) {
            $this->line('Abgebrochen – die Datei bleibt, wie sie ist.');

            return self::SUCCESS;
        }

        File::ensureDirectoryExists(dirname($datei));
        File::put($datei, $projekt->body ?? '');

        $this->info("{$projekt->title}: ".($projekt->body ? 'herausgeholt' : 'noch leer, Datei angelegt'));
        $this->newLine();
        $this->line('  nano '.$datei);
        $this->line('  php artisan nd:fallstudie '.$projekt->slug.' --zurueckschreiben');

        return self::SUCCESS;
    }

    private function zurueck(Project $projekt, string $datei): int
    {
        if (! File::exists($datei)) {
            $this->error("Keine Datei unter {$datei}.");
            $this->line('Erst herausholen: php artisan nd:fallstudie '.$projekt->slug);

            return self::FAILURE;
        }

        $text = trim(File::get($datei));

        // Ein leerer Text setzt die Detailseite zurück auf noindex. Das kann
        // gewollt sein, soll aber nicht unbemerkt passieren.
        if ($text === '' && ! $this->confirm('Die Datei ist leer. Fallstudie wirklich löschen? Die Seite fällt damit zurück auf noindex.')) {
            return self::SUCCESS;
        }

        $projekt->update(['body' => $text ?: null]);

        $this->info("{$projekt->title}: übernommen (".mb_strlen($text).' Zeichen).');

        if ($text !== '') {
            $this->line('  Detailseite: '.route('projekte.show', $projekt));
            $this->newLine();
            $this->warn('Nicht vergessen: php artisan nd:inhalt-ausgeben, sonst reist der alte Stand mit.');
        }

        return self::SUCCESS;
    }
}
