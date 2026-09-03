<?php

namespace App\Console\Commands;

use App\Support\Inhalt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Schreibt alle Inhalte in eine Datei.
 *
 * Das Gegenstück zu nd:inhalt-einlesen. Beide zusammen sind der Weg, den
 * CLAUDE.md als offenen Punkt führt: der lokale Stand wird geschlossen auf den
 * Server gebracht, weil der Deploy Inhalte bewusst nicht anfasst.
 *
 * Bewusst JSON und kein Datenbankabzug: lokal läuft SQLite, auf dem Server
 * PostgreSQL. Ein Dump der einen Seite ist auf der anderen wertlos. JSON ist
 * ausserdem lesbar – man sieht im Diff, was man überträgt.
 */
class InhaltAusgeben extends Command
{
    protected $signature = 'nd:inhalt-ausgeben
                            {--datei= : Zieldatei (Vorgabe: database/inhalt/inhalt.json)}';

    protected $description = 'Schreibt Beiträge, Projekte, Leistungen, Kundenstimmen und Team in eine JSON-Datei';

    public function handle(): int
    {
        $datei = $this->option('datei') ?: database_path('inhalt/inhalt.json');

        $daten = [
            'erzeugt' => now()->toIso8601String(),
            'quelle' => config('app.url'),
            'tabellen' => [],
        ];

        foreach (Inhalt::TABELLEN as $tabelle) {
            $zeilen = DB::table($tabelle)->get()->map(fn ($z) => (array) $z)->all();
            $daten['tabellen'][$tabelle] = $zeilen;

            $this->line(sprintf('  %-20s %d', $tabelle, count($zeilen)));
        }

        File::ensureDirectoryExists(dirname($datei));
        File::put($datei, json_encode($daten, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n");

        $this->newLine();
        $this->info('Geschrieben nach '.$datei);

        $this->pruefung();

        return self::SUCCESS;
    }

    /**
     * Meldet Bilder, auf die Inhalte zeigen, die es aber nicht gibt.
     *
     * Die JSON-Datei trägt nur Verweise. Fehlt eine Datei schon hier, fehlt sie
     * auf dem Ziel erst recht – und dort fällt es niemandem auf, weil dort
     * niemand die Seite durchklickt.
     */
    private function pruefung(): void
    {
        $funde = [];

        $verweise = [
            'posts' => ['hero_image'],
            'projects' => ['image'],
            'team_members' => ['image'],
        ];

        foreach ($verweise as $tabelle => $spalten) {
            foreach ($spalten as $spalte) {
                foreach (DB::table($tabelle)->whereNotNull($spalte)->get() as $zeile) {
                    $pfad = public_path(ltrim($zeile->{$spalte}, '/'));

                    if (! File::exists($pfad)) {
                        $funde[] = "{$tabelle}#{$zeile->id}: {$zeile->{$spalte}}";
                    }
                }
            }
        }

        if ($funde === []) {
            return;
        }

        $this->newLine();
        $this->warn('Verweise ins Leere ('.count($funde).') – diese Bilder fehlen auch auf dem Ziel:');
        foreach ($funde as $fund) {
            $this->warn("  {$fund}");
        }
    }
}
