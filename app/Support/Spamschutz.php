<?php

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

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
 * Drei Fallen, alle ohne JavaScript:
 * - der Honigtopf "website", den nur Roboter ausfüllen;
 * - ein signierter Zeitstempel: der Roboter schickte 0 bis 2 Sekunden nach
 *   dem Abruf ab, so schnell füllt kein Mensch ein Formular aus;
 * - ein Link im Namen, den kein Mensch dort hineinschreibt.
 *
 * Wer hineinfällt, bekommt dieselbe Antwort wie ein Mensch, aber es wird
 * nichts gespeichert und nichts verschickt. Eine Fehlermeldung verriete dem
 * Roboter, woran er gescheitert ist.
 */
class Spamschutz
{
    public const FELD = 'zeitstempel';

    /** Schneller schickt kein Mensch ein Formular ab. */
    public const MINDESTSEKUNDEN = 3;

    /** Eingangsbestätigungen je Stunde, beide Formulare und alle Absender zusammen. */
    public const BESTAETIGUNGEN_JE_STUNDE = 20;

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

    /** Ist eine der drei Fallen zugeschnappt? Wenn ja, steht der Grund im Log. */
    public static function istRoboter(Request $request, string $formular): bool
    {
        $grund = match (true) {
            filled($request->input('website')) => 'Honigtopf',
            self::zuSchnell($request->input(self::FELD)) => 'zu schnell',
            self::hatLink($request->input('name')) => 'Link im Namen',
            default => null,
        };

        if ($grund) {
            Log::notice("Spamschutz: {$formular} verworfen ({$grund})", ['ip' => $request->ip()]);
        }

        return $grund !== null;
    }

    /**
     * Darf noch eine Eingangsbestätigung raus?
     *
     * Die letzte Sicherung, falls doch einmal etwas durch die Fallen kommt:
     * Die Anfrage selbst wird trotzdem gespeichert und übergeben, nur die Mail
     * an die angegebene – womöglich fremde – Adresse entfällt.
     */
    public static function bestaetigungErlaubt(): bool
    {
        if (RateLimiter::tooManyAttempts('bestaetigungen', self::BESTAETIGUNGEN_JE_STUNDE)) {
            Log::warning('Spamschutz: Obergrenze für Eingangsbestätigungen erreicht');

            return false;
        }

        RateLimiter::hit('bestaetigungen', 3600);

        return true;
    }

    private static function zuSchnell(mixed $stempel): bool
    {
        try {
            $ausgeliefert = (int) Crypt::decryptString((string) $stempel);
        } catch (DecryptException) {
            return true;
        }

        return now()->getTimestamp() - $ausgeliefert < self::MINDESTSEKUNDEN;
    }

    private static function hatLink(mixed $name): bool
    {
        return is_string($name) && preg_match('~https?:|://|www\.~i', $name) === 1;
    }
}
