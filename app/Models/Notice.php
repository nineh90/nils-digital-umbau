<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Ein Hinweis, der beim Aufruf der Seite erscheint.
 *
 * Sichtbar wird er über <x-hinweis>, gepflegt wird er unter /admin.
 */
class Notice extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Die fertigen Farbschemata.
     *
     * Sie stehen hier und nicht im Blade, weil die Redaktion dieselben Namen
     * zur Auswahl braucht – dasselbe Muster wie bei App\Support\Symbole.
     * Die Farben selbst liegen als CSS-Klassen in app.css und kommen von dort
     * aus den Design-Tokens.
     *
     * @return array<string, string>
     */
    public static function schemata(): array
    {
        return [
            'akzent' => 'Akzent – Türkis, der normale Fall',
            'dezent' => 'Dezent – zurückhaltend, für Sachhinweise',
            'warnung' => 'Warnung – Bernstein, für Betriebsferien oder Störungen',
            'festlich' => 'Festlich – Tannengrün und warmes Gold, für Weihnachten',
            'aktion' => 'Aktion – kräftig, für Rabatte und Angebote',
        ];
    }

    /** @return array<string, string> */
    public static function darstellungen(): array
    {
        return [
            'center' => 'Mitte – Fenster über der Seite, größte Aufmerksamkeit',
            'top' => 'Leiste oben – schmales Band, stört am wenigsten',
            'corner' => 'Ecke unten rechts – kleine Karte, dezent',
        ];
    }

    /** @return array<string, string> */
    public static function haeufigkeiten(): array
    {
        return [
            'once' => 'Einmal je Besucher – bis er den Browserspeicher leert',
            'session' => 'Einmal je Besuch – wieder beim nächsten Mal',
            'always' => 'Bei jedem Seitenaufruf – nur für Notfälle',
        ];
    }

    /**
     * Was gerade gezeigt werden darf.
     *
     * Zeitraum offen heißt "ab sofort" beziehungsweise "bis auf Weiteres" –
     * beide Grenzen dürfen fehlen.
     */
    public function scopeAktiv(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }

    /**
     * Der eine Hinweis, der angezeigt wird.
     *
     * Bewusst nur einer: zwei Fenster gleichzeitig sind keine Botschaft mehr,
     * sondern eine Zumutung. Bei mehreren gültigen entscheidet die Reihenfolge.
     */
    public static function aktueller(): ?self
    {
        /*
         * Bewusst ohne once() oder anderen Zwischenspeicher.
         *
         * once() merkt sich das Ergebnis nicht je Anfrage, sondern für die
         * Lebensdauer des Prozesses. Unter PHP-FPM fällt das nicht auf, im Test
         * und unter einem dauerhaft laufenden Server schon: dort bliebe der
         * erste Stand hängen, und ein gerade eingeschalteter Hinweis erschiene
         * nicht – ohne dass irgendwo ein Fehler auftaucht.
         *
         * Die Komponente wird zweimal eingebunden, das sind also zwei Abfragen
         * auf eine Tabelle mit einer Handvoll Zeilen. Der Preis ist es wert.
         */
        return static::aktiv()->orderBy('position')->orderByDesc('id')->first();
    }

    /**
     * Schlüssel für den Browserspeicher.
     *
     * Die Uhrzeit der letzten Änderung steckt mit drin: wird ein Hinweis
     * überarbeitet, ist es für den Besucher ein neuer – sonst bekäme er die
     * Korrektur nie zu sehen, weil er das alte Fenster schon weggeklickt hat.
     */
    public function speicherSchluessel(): string
    {
        return 'hinweis-'.$this->id.'-'.$this->updated_at?->timestamp;
    }
}
