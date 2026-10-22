<?php

namespace App\Http\Controllers;

use App\Http\Requests\KontaktRequest;
use App\Jobs\AnfrageUebergeben;
use App\Mail\KontaktAnfrage;
use App\Mail\KontaktBestaetigung;
use App\Models\Inquiry;
use App\Support\Spamschutz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Nimmt das Kontaktformular entgegen.
 *
 * Ablösung für legacy/backend/contact.php. Zwei Dinge sind anders und besser:
 * Die Zugangsdaten stehen in der .env statt in einer Datei neben dem Skript,
 * und der Versand läuft über die Warteschlange – das Formular wartet nicht mehr
 * auf den SMTP-Server, während der Besucher vor einem drehenden Rad sitzt.
 */
class KontaktController extends Controller
{
    public function senden(KontaktRequest $request): RedirectResponse
    {
        $daten = $request->validated();

        if (Spamschutz::stempelFehlt($request)) {
            return back()
                ->withInput()
                ->withErrors(['message' => 'Die Seite war zu lange offen. Bitte lade sie neu und schick deine Nachricht noch einmal ab.']);
        }

        if (Spamschutz::istRoboter($request, 'Kontaktformular')) {
            return $this->erfolg();
        }

        // Gegen Massenversand über das Formular. Die alte Fassung hatte nur
        // den Honigtopf – der hält Roboter auf, aber niemanden, der das
        // Formular von Hand missbraucht.
        $schluessel = 'kontakt:'.$request->ip();

        if (RateLimiter::tooManyAttempts($schluessel, 5)) {
            return back()
                ->withInput()
                ->withErrors(['message' => 'Zu viele Anfragen. Bitte versuch es in einer Stunde noch einmal.']);
        }

        RateLimiter::hit($schluessel, 3600);

        // Erst speichern, dann verschicken. Die Mail ist die Benachrichtigung;
        // geht sie verloren, steht die Anfrage trotzdem in der Redaktion.
        $anfrage = Inquiry::create([
            'type' => 'kontakt',
            'name' => $daten['name'],
            'email' => $daten['email'],
            'subject' => $daten['subject'],
            'message' => $daten['message'],
        ]);

        Mail::to(config('mail.from.address'))->queue(new KontaktAnfrage(
            $daten['name'], $daten['email'], $daten['subject'], $daten['message'],
        ));

        if (Spamschutz::bestaetigungErlaubt()) {
            Mail::to($daten['email'])->queue(new KontaktBestaetigung);
        }

        AnfrageUebergeben::dispatch($anfrage);

        return $this->erfolg();
    }

    private function erfolg(): RedirectResponse
    {
        return redirect()
            ->route('kontakt')
            ->with('erfolg', 'Danke! Deine Nachricht ist angekommen – ich melde mich in der Regel innerhalb eines Werktags.');
    }
}
