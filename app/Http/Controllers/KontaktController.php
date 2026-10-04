<?php

namespace App\Http\Controllers;

use App\Http\Requests\KontaktRequest;
use App\Jobs\AnfrageUebergeben;
use App\Mail\KontaktAnfrage;
use App\Mail\KontaktBestaetigung;
use App\Models\Inquiry;
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

        Mail::to($daten['email'])->queue(new KontaktBestaetigung(
            $daten['name'], $daten['subject'], $daten['message'],
        ));

        AnfrageUebergeben::dispatch($anfrage);

        return redirect()
            ->route('kontakt')
            ->with('erfolg', 'Danke! Deine Nachricht ist angekommen – ich melde mich in der Regel innerhalb eines Werktags.');
    }
}
