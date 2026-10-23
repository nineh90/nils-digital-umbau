<?php

namespace App\Http\Controllers;

use App\Http\Requests\KontaktRequest;
use App\Jobs\AnfrageUebergeben;
use App\Mail\KontaktAnfrage;
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
            return $this->erfolg($daten);
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

        // Keine Eingangsbestätigung an die angegebene Adresse: Sie war im
        // Oktober 2026 das, worauf es der Roboter abgesehen hatte, siehe
        // App\Support\Spamschutz. Was sie leisten sollte, übernimmt die
        // Meldung auf der Seite.
        AnfrageUebergeben::dispatch($anfrage);

        return $this->erfolg($daten);
    }

    /**
     * Die Meldung nennt Name, Betreff und Adresse, damit man ohne
     * Bestätigungsmail sieht, was angekommen ist – und einen Tippfehler in
     * der Adresse noch bemerkt. Roboter bekommen dieselbe Meldung; sie sehen
     * nur, was sie selbst geschickt haben.
     */
    private function erfolg(array $daten): RedirectResponse
    {
        return redirect()
            ->route('kontakt')
            ->with('erfolg', [
                'name' => $daten['name'],
                'betreff' => $daten['subject'],
                'email' => $daten['email'],
            ]);
    }
}
