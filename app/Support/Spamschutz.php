<?php

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/**
 * Schutz der beiden Formulare vor Robotern.
 *
 * Anlass war der Oktober 2026: ein Roboter schickte über das Kontaktformular
 * Hunderte Anfragen mit fremden Adressen und einem Betrugslink im Namen. Ziel
 * war nicht Nils' Postfach, sondern die Eingangsbestätigung – sie trug den
 * Link an die fremde Adresse weiter, abgeschickt von unserer Domain. Der
 * Honigtopf allein hielt ihn nicht auf, die Drosselung je IP verteilte er auf
 * mehrere Adressen.
 *
 * Einen Tag später kam er wieder, diesmal mit dem Link in der Nachricht und
 * mit einer Pause zwischen Abruf und Absenden, von der etwa jede fünfzigste
 * über drei Sekunden lag. Seitdem gibt es gar keine Eingangsbestätigung mehr –
 * damit ist das Formular für diese Masche wertlos –, und die Fallen sind
 * schärfer:
 *
 * - der Honigtopf "website", den nur Roboter ausfüllen;
 * - ein signierter Zeitstempel: schneller als MINDESTSEKUNDEN füllt kein
 *   Mensch das Formular aus;
 * - ein Link im Namen, den kein Mensch dort hineinschreibt;
 * - kyrillische Schrift. Alle Wellen bisher kamen auf Russisch, und eine
 *   echte Anfrage auf Russisch hat Nils-Digital nie bekommen.
 *
 * Wer hineinfällt, bekommt dieselbe Antwort wie ein Mensch, aber es wird
 * nichts gespeichert und nichts verschickt. Eine Fehlermeldung verriete dem
 * Roboter, woran er gescheitert ist.
 */
class Spamschutz
{
    public const FELD = 'zeitstempel';

    /**
     * Schneller schickt kein Mensch das Formular ab. Beim Kontaktformular
     * sind vier Felder zu schreiben, darunter eine Nachricht; im Fragebogen
     * trägt der letzte Schritt nur Name und Adresse, die der Browser oft
     * selbst einsetzt – dort reichen deshalb wenige Sekunden.
     */
    public const MINDESTSEKUNDEN = [
        'Kontaktformular' => 10,
        'Projektanfrage' => 3,
    ];

    /** Inhalt des versteckten Feldes: der Zeitpunkt, an dem das Formular ausgeliefert wurde. */
    public static function stempel(?Carbon $zeit = null): string
    {
        return Crypt::encryptString((string) ($zeit ?? now())->getTimestamp());
    }

    /**
     * Fehlt der Zeitstempel ganz? Das ist kein Roboter, sondern eine Seite,
     * die schon vor dem Einbau dieser Falle offen war – sie bekommt eine
     * sichtbare Bitte, neu zu laden, statt still verworfen zu werden.
     */
    public static function stempelFehlt(Request $request): bool
    {
        return blank($request->input(self::FELD));
    }

    /** Ist eine der Fallen zugeschnappt? Wenn ja, steht der Grund im Log. */
    public static function istRoboter(Request $request, string $formular): bool
    {
        $grund = match (true) {
            filled($request->input('website')) => 'Honigtopf',
            self::zuSchnell($request->input(self::FELD), self::MINDESTSEKUNDEN[$formular] ?? 3) => 'zu schnell',
            self::hatLink($request->input('name')) => 'Link im Namen',
            self::hatKyrillisch($request->except(self::FELD, '_token')) => 'kyrillisch',
            default => null,
        };

        if ($grund) {
            Log::notice("Spamschutz: {$formular} verworfen ({$grund})", ['ip' => $request->ip()]);
        }

        return $grund !== null;
    }

    private static function zuSchnell(mixed $stempel, int $mindestsekunden): bool
    {
        try {
            $ausgeliefert = (int) Crypt::decryptString((string) $stempel);
        } catch (DecryptException) {
            return true;
        }

        return now()->getTimestamp() - $ausgeliefert < $mindestsekunden;
    }

    private static function hatLink(mixed $name): bool
    {
        return is_string($name) && preg_match('~https?:|://|www\.~i', $name) === 1;
    }

    /** @param array<string, mixed> $eingaben */
    private static function hatKyrillisch(array $eingaben): bool
    {
        return collect($eingaben)
            ->flatten()
            ->contains(fn ($wert) => is_string($wert) && preg_match('~\p{Cyrillic}~u', $wert) === 1);
    }
}
