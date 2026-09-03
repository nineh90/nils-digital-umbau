<?php

namespace App\Console\Commands;

use App\Support\Inhalt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

/**
 * Liest eine mit nd:inhalt-ausgeben erzeugte Datei ein und ersetzt die Inhalte.
 *
 * Ersetzen, nicht ergänzen: gäbe es einen Mischmodus, bliebe auf dem Ziel
 * stehen, was hier gelöscht wurde – etwa die 22 ausgedünnten Blogbeiträge, die
 * dann wieder aufträten. Der Befehl bildet deshalb den Stand der Quelle ab,
 * vollständig und mit denselben IDs.
 *
 * Dieselben IDs sind kein Selbstzweck: post_project verknüpft darüber, und
 * posts.legacy_id trägt die 301-Weiterleitungen der alten Adressen.
 */
class InhaltEinlesen extends Command
{
    protected $signature = 'nd:inhalt-einlesen
                            {--datei= : Quelldatei (Vorgabe: database/inhalt/inhalt.json)}
                            {--ohne-sicherung : Vorherigen Stand nicht wegschreiben}
                            {--force : Ohne Rückfrage ausführen}';

    protected $description = 'Ersetzt Beiträge, Projekte, Leistungen, Kundenstimmen und Team durch den Stand aus der Datei';

    public function handle(): int
    {
        $datei = $this->option('datei') ?: database_path('inhalt/inhalt.json');

        if (! File::exists($datei)) {
            $this->error("Keine Datei unter {$datei}.");

            return self::FAILURE;
        }

        $daten = json_decode(File::get($datei), true);

        if (! is_array($daten) || ! isset($daten['tabellen'])) {
            $this->error('Die Datei ist keine Ausgabe von nd:inhalt-ausgeben.');

            return self::FAILURE;
        }

        $this->line('Datei vom '.($daten['erzeugt'] ?? 'unbekannt').', erzeugt auf '.($daten['quelle'] ?? 'unbekannt'));
        $this->newLine();

        foreach (Inhalt::TABELLEN as $tabelle) {
            $this->line(sprintf(
                '  %-20s %4d hier  →  %4d aus der Datei',
                $tabelle,
                DB::table($tabelle)->count(),
                count($daten['tabellen'][$tabelle] ?? [])
            ));
        }

        $this->newLine();

        if (! $this->option('force') && ! $this->confirm('Diese Tabellen werden geleert und neu befüllt. Weiter?')) {
            $this->line('Abgebrochen.');

            return self::SUCCESS;
        }

        if (! $this->option('ohne-sicherung')) {
            $this->sicherung($datei);
        }

        try {
            DB::transaction(function () use ($daten) {
                // Rückwärts leeren: Kinder vor Eltern, sonst hängt ein
                // Fremdschlüssel an einer Zeile, die es nicht mehr gibt.
                foreach (array_reverse(Inhalt::TABELLEN) as $tabelle) {
                    DB::table($tabelle)->delete();
                }

                foreach (Inhalt::TABELLEN as $tabelle) {
                    $zeilen = $daten['tabellen'][$tabelle] ?? [];

                    foreach (array_chunk($zeilen, 200) as $haeufchen) {
                        DB::table($tabelle)->insert($haeufchen);
                    }

                    $this->line(sprintf('  %-20s %d eingelesen', $tabelle, count($zeilen)));
                }
            });
        } catch (Throwable $e) {
            $this->error('Fehlgeschlagen, nichts geändert: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->zaehlerNachziehen();

        $this->newLine();
        $this->info('Inhalte übernommen.');

        return self::SUCCESS;
    }

    /**
     * Der Stolperstein bei PostgreSQL.
     *
     * Die IDs kommen aus der Datei, der Zähler der Sequenz weiß davon nichts
     * und steht weiter auf 1. Der nächste in der Redaktion angelegte Beitrag
     * liefe dann in eine Kollision – und zwar erst irgendwann später, wenn
     * niemand mehr an den Transfer denkt. SQLite zieht seinen Zähler beim
     * Einfügen selbst nach, dort ist nichts zu tun.
     */
    private function zaehlerNachziehen(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        foreach (Inhalt::TABELLEN as $tabelle) {
            // post_project hat keine eigene id-Spalte.
            if (! DB::getSchemaBuilder()->hasColumn($tabelle, 'id')) {
                continue;
            }

            DB::statement("
                SELECT setval(
                    pg_get_serial_sequence('{$tabelle}', 'id'),
                    COALESCE((SELECT MAX(id) FROM {$tabelle}), 1),
                    (SELECT MAX(id) IS NOT NULL FROM {$tabelle})
                )
            ");
        }

        $this->line('  Sequenzen nachgezogen.');
    }

    /** Schreibt den bisherigen Stand daneben, damit es einen Weg zurück gibt. */
    private function sicherung(string $datei): void
    {
        $ziel = dirname($datei).'/vorher-'.now()->format('Y-m-d-His').'.json';

        $this->callSilently('nd:inhalt-ausgeben', ['--datei' => $ziel]);

        $this->line('Bisheriger Stand gesichert: '.$ziel);
    }
}
