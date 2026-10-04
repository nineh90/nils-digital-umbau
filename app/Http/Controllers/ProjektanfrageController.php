<?php

namespace App\Http\Controllers;

use App\Jobs\AnfrageUebergeben;
use App\Mail\ProjektanfrageBestaetigung;
use App\Mail\ProjektanfrageEingang;
use App\Models\Inquiry;
use App\Support\Fragebogen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * Der Projektfragebogen in vier Schritten (NID-19).
 *
 * Ablösung für das eingebettete Google-Formular. Jeder Schritt ist eine eigene
 * Seite mit einem eigenen Absenden – das funktioniert ohne eine Zeile
 * JavaScript, der Zurück-Knopf des Browsers tut, was man erwartet, und eine
 * Fehlermeldung steht neben der Frage, zu der sie gehört.
 *
 * Die Antworten liegen bis zum Schluss in der Sitzung. In die Datenbank kommt
 * erst die vollständige Anfrage: ein halb ausgefüllter Bogen ist kein Lead,
 * und wer abbricht, hat uns nichts geschickt.
 */
class ProjektanfrageController extends Controller
{
    private const SITZUNG = 'fragebogen';

    private const ERGEBNIS = 'fragebogen_ergebnis';

    public function zeigen(Request $request, string $schritt = 'vorhaben'): View|RedirectResponse
    {
        $antworten = $request->session()->get(self::SITZUNG, []);

        // Wer einen späteren Schritt direkt aufruft, landet beim ersten, der
        // noch fehlt – sonst stünde er vor Fragen ohne Zusammenhang.
        if ($fehlt = $this->ersterOffenerSchritt($schritt, $antworten)) {
            return redirect()->to($this->adresse($fehlt));
        }

        $nummer = array_search($schritt, Fragebogen::SCHRITTE) + 1;

        return view('seiten.projektanfrage', [
            'schritt' => $schritt,
            'nummer' => $nummer,
            'inhalt' => Fragebogen::schritt($schritt, $antworten),
            'antworten' => $antworten,
            'zurueck' => $nummer > 1 ? $this->adresse(Fragebogen::SCHRITTE[$nummer - 2]) : null,
            'letzter' => $nummer === count(Fragebogen::SCHRITTE),
        ]);
    }

    public function speichern(Request $request, string $schritt): RedirectResponse
    {
        $antworten = $request->session()->get(self::SITZUNG, []);

        if ($fehlt = $this->ersterOffenerSchritt($schritt, $antworten)) {
            return redirect()->to($this->adresse($fehlt));
        }

        $regeln = Fragebogen::regeln($schritt, $antworten);
        $letzter = $schritt === 'kontakt';

        if ($letzter) {
            // Honigtopf wie im Kontaktformular: für Menschen unsichtbar.
            $regeln['website'] = ['nullable', 'prohibited'];
        }

        $neu = $request->validate($regeln, [
            'required' => 'Bitte beantworte :attribute.',
            'unternehmer.accepted' => 'Bitte bestätige, dass du nicht als Privatperson anfragst.',
            'website.prohibited' => 'Deine Anfrage konnte nicht gesendet werden.',
        ], Fragebogen::bezeichnungen($schritt, $antworten));

        unset($neu['website']);

        // Ein anderes Vorhaben bringt andere Detailfragen mit. Die alten
        // Antworten blieben sonst liegen und flössen in den Preis ein.
        if ($schritt === 'vorhaben' && ($antworten['vorhaben'] ?? null) !== $neu['vorhaben']) {
            $antworten = [];
        }

        // Nicht angekreuzte Mehrfachauswahl kommt im Request gar nicht vor –
        // ohne das Leeren bliebe die frühere Auswahl stehen.
        foreach (Fragebogen::schritt($schritt, $antworten)['felder'] as $feld) {
            unset($antworten[$feld['name']]);
        }

        $antworten = array_merge($antworten, $neu);
        $request->session()->put(self::SITZUNG, $antworten);

        if (! $letzter) {
            $naechster = Fragebogen::SCHRITTE[array_search($schritt, Fragebogen::SCHRITTE) + 1];

            return redirect()->route('projektanfrage.schritt', $naechster);
        }

        // Gegen Massenversand, wie beim Kontaktformular. Erst hier, weil erst
        // dieser Schritt etwas speichert und Mails auslöst.
        $schluessel = 'projektanfrage:'.$request->ip();

        if (RateLimiter::tooManyAttempts($schluessel, 5)) {
            return back()->withInput()->withErrors([
                'name' => 'Zu viele Anfragen. Bitte versuch es in einer Stunde noch einmal.',
            ]);
        }

        RateLimiter::hit($schluessel, 3600);

        $anfrage = $this->anlegen($antworten);

        Mail::to(config('mail.from.address'))->queue(new ProjektanfrageEingang($anfrage));
        Mail::to($anfrage->email)->queue(new ProjektanfrageBestaetigung($anfrage));
        AnfrageUebergeben::dispatch($anfrage);

        $request->session()->forget(self::SITZUNG);
        $request->session()->put(self::ERGEBNIS, $anfrage->id);

        return redirect()->route('projektanfrage.danke');
    }

    public function danke(Request $request): View|RedirectResponse
    {
        $anfrage = Inquiry::find($request->session()->get(self::ERGEBNIS));

        if (! $anfrage) {
            return redirect()->route('projektanfrage');
        }

        return view('seiten.projektanfrage-danke', [
            'anfrage' => $anfrage,
            'preisZeilen' => Fragebogen::preisZeilen($anfrage->details['preis']),
            'zusammenfassung' => $anfrage->details['zusammenfassung'],
        ]);
    }

    /**
     * Der erste Schritt hat nur eine Adresse: /projektanfrage. Unter
     * /projektanfrage/vorhaben stünde dieselbe Seite ein zweites Mal.
     */
    private function adresse(string $schritt): string
    {
        return $schritt === Fragebogen::SCHRITTE[0]
            ? route('projektanfrage')
            : route('projektanfrage.schritt', $schritt);
    }

    /**
     * Der erste Schritt vor $schritt, dessen Antworten fehlen oder nicht mehr
     * gültig sind. Null, wenn alles davor steht.
     */
    private function ersterOffenerSchritt(string $schritt, array $antworten): ?string
    {
        foreach (Fragebogen::SCHRITTE as $kandidat) {
            if ($kandidat === $schritt) {
                return null;
            }

            if (Validator::make($antworten, Fragebogen::regeln($kandidat, $antworten))->fails()) {
                return $kandidat;
            }
        }

        return null;
    }

    private function anlegen(array $antworten): Inquiry
    {
        $zusammenfassung = Fragebogen::zusammenfassung($antworten);
        $preis = Fragebogen::preis($antworten);

        /*
         * message trägt den Bogen als lesbaren Text. Damit zeigen die
         * Redaktion und jede spätere Übergabe an das Kundensystem die Anfrage
         * ohne Sonderbehandlung an; die Rohdaten stehen daneben in details.
         */
        $text = collect($zusammenfassung)
            ->map(fn ($zeile) => $zeile['frage'].': '.$zeile['antwort'])
            ->when(filled($antworten['firma'] ?? null), fn ($z) => $z->push('Firma: '.$antworten['firma']))
            ->when(filled($antworten['telefon'] ?? null), fn ($z) => $z->push('Telefon: '.$antworten['telefon']))
            ->when(filled($antworten['nachricht'] ?? null), fn ($z) => $z->push('', 'Nachricht:', $antworten['nachricht']))
            ->implode("\n");

        return Inquiry::create([
            'type' => 'projektanfrage',
            'name' => $antworten['name'],
            'email' => $antworten['email'],
            'subject' => 'Projektanfrage: '.Fragebogen::VORHABEN[$antworten['vorhaben']]['label'],
            'message' => $text,
            'details' => [
                'antworten' => $antworten,
                'zusammenfassung' => $zusammenfassung,
                'preis' => $preis,
            ],
        ]);
    }
}
