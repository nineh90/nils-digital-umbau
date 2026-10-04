<?php

namespace App\Support;

use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

/**
 * Der Projektfragebogen: welche Fragen in welchem Schritt, und was am Ende
 * als Richtpreis herauskommt.
 *
 * Löst das Google-Formular ab (NID-19). Die Fragen stehen hier und nicht im
 * Blade, weil vier Stellen dieselbe Liste brauchen: die Seite, die Prüfung der
 * Eingaben, die Zusammenfassung in der Mail und die Preisberechnung. Vier
 * Listen, die dasselbe meinen, laufen zuverlässig auseinander.
 *
 * Preise stehen hier bewusst KEINE. Jede Antwort, die etwas kostet, verweist
 * über 'leistung' auf den slug einer Zeile in services – der Betrag kommt aus
 * der Redaktion. Wer dort einen Preis ändert, ändert ihn damit auch hier.
 */
class Fragebogen
{
    /** @var list<string> */
    public const SCHRITTE = ['vorhaben', 'details', 'rahmen', 'kontakt'];

    public const TITEL = [
        'vorhaben' => 'Vorhaben',
        'details' => 'Details',
        'rahmen' => 'Rahmen',
        'kontakt' => 'Kontakt',
    ];

    /**
     * Was jemand vorhat. Der Schlüssel entscheidet, welche Fragen im zweiten
     * Schritt kommen.
     */
    public const VORHABEN = [
        'website-neu' => [
            'label' => 'Neue Website',
            'text' => 'Du hast noch keine – oder willst ganz neu anfangen.',
        ],
        'website-ueberarbeiten' => [
            'label' => 'Bestehende Website überarbeiten',
            'text' => 'Es gibt schon eine Seite, aber sie passt nicht mehr.',
        ],
        'webanwendung' => [
            'label' => 'Webanwendung oder Kundenportal',
            'text' => 'Mehr als eine Website: Anmeldung, Verwaltung, eigene Abläufe.',
        ],
        'ki' => [
            'label' => 'KI-Automatisierung',
            'text' => 'Wiederkehrende Arbeit abgeben: Abläufe, Chatbot, Dokumente, E-Mails.',
        ],
        'unsicher' => [
            'label' => 'Weiß ich noch nicht genau',
            'text' => 'Du hast ein Problem, aber noch keinen Namen dafür. Auch gut.',
        ],
    ];

    /**
     * Überschrift, Einleitung und Felder eines Schritts.
     *
     * @param  array<string, mixed>  $antworten  bisherige Antworten – der
     *         zweite Schritt hängt vom ersten ab
     * @return array{ueberschrift: string, text: string, felder: list<array<string, mixed>>}
     */
    public static function schritt(string $schritt, array $antworten = []): array
    {
        return match ($schritt) {
            'vorhaben' => [
                'ueberschrift' => 'Worum geht es bei deinem Projekt?',
                'text' => 'Such dir aus, was am ehesten passt. Die nächsten Fragen richten sich danach.',
                'felder' => [[
                    'name' => 'vorhaben',
                    'typ' => 'auswahl',
                    'frage' => 'Dein Vorhaben',
                    'pflicht' => true,
                    'optionen' => self::VORHABEN,
                ]],
            ],
            'details' => self::details($antworten['vorhaben'] ?? ''),
            'rahmen' => [
                'ueberschrift' => 'Wie soll es laufen?',
                'text' => 'Zahlweise und Zeit – damit wir den Preis passend nennen können.',
                'felder' => [
                    [
                        'name' => 'zahlweise',
                        'typ' => 'auswahl',
                        'frage' => 'Wie möchtest du zahlen?',
                        'pflicht' => true,
                        'optionen' => [
                            'abo' => ['label' => 'Monatlich im Abo', 'text' => 'Kleine Einrichtung, danach ein fester Monatsbetrag. Pflege ist enthalten.'],
                            'festpreis' => ['label' => 'Einmalig zum Festpreis', 'text' => 'Du zahlst die Erstellung auf einmal, die Seite gehört dir.'],
                            'offen' => ['label' => 'Weiß ich noch nicht', 'text' => 'Wir nennen dir beide Preise.'],
                        ],
                    ],
                    [
                        'name' => 'zeitrahmen',
                        'typ' => 'auswahl',
                        'frage' => 'Bis wann soll es fertig sein?',
                        'pflicht' => true,
                        'optionen' => [
                            'sofort' => ['label' => 'So schnell wie möglich'],
                            'quartal' => ['label' => 'In den nächsten ein bis drei Monaten'],
                            'flexibel' => ['label' => 'Ich bin flexibel'],
                        ],
                    ],
                ],
            ],
            'kontakt' => [
                'ueberschrift' => 'Wohin dürfen wir antworten?',
                'text' => 'Fast geschafft. Danach siehst du sofort deinen Richtpreis – und bekommst ihn per E-Mail.',
                'felder' => [
                    ['name' => 'name', 'typ' => 'text', 'frage' => 'Name', 'pflicht' => true, 'autocomplete' => 'name', 'halb' => true],
                    ['name' => 'firma', 'typ' => 'text', 'frage' => 'Firma oder Organisation', 'pflicht' => false, 'autocomplete' => 'organization', 'halb' => true],
                    ['name' => 'email', 'typ' => 'email', 'frage' => 'E-Mail', 'pflicht' => true, 'autocomplete' => 'email', 'halb' => true],
                    ['name' => 'telefon', 'typ' => 'tel', 'frage' => 'Telefon', 'pflicht' => false, 'autocomplete' => 'tel', 'halb' => true, 'hilfe' => 'Nur wenn du lieber angerufen wirst.'],
                    ['name' => 'nachricht', 'typ' => 'absatz', 'frage' => 'Möchtest du uns noch etwas mitgeben?', 'pflicht' => false],
                    [
                        'name' => 'unternehmer',
                        'typ' => 'haken',
                        'frage' => 'Ich frage für ein Unternehmen, eine selbstständige Tätigkeit oder eine Organisation an – nicht als Privatperson.',
                        'pflicht' => true,
                        'hilfe' => 'Unsere Angebote richten sich an Unternehmen. Als Privatperson schreib uns bitte über das Kontaktformular.',
                    ],
                ],
            ],
        };
    }

    /**
     * Die projektspezifischen Fragen des zweiten Schritts.
     *
     * @return array{ueberschrift: string, text: string, felder: list<array<string, mixed>>}
     */
    private static function details(string $vorhaben): array
    {
        $umfang = [
            'name' => 'umfang',
            'typ' => 'auswahl',
            'frage' => 'Wie umfangreich soll die Website werden?',
            'pflicht' => true,
            'optionen' => [
                'onepager' => ['label' => 'Eine Seite (Onepager)', 'text' => 'Alles Wichtige auf einen Blick.', 'leistung' => 'basic-website'],
                'mittel' => ['label' => 'Zwei bis vier Seiten', 'text' => 'Start, Leistungen, Über uns, Kontakt.', 'leistung' => 'standard-website'],
                'gross' => ['label' => 'Fünf Seiten oder mehr', 'text' => 'Mehrere Bereiche, Unterseiten je Leistung.', 'leistung' => 'pro-website'],
                'offen' => ['label' => 'Kann ich noch nicht sagen', 'text' => 'Wir rechnen mit dem kleinsten Paket und schauen gemeinsam.', 'leistung' => 'basic-website'],
            ],
        ];

        $erweiterungen = [
            'name' => 'erweiterungen',
            'typ' => 'mehrfach',
            'frage' => 'Soll noch etwas dazu?',
            'pflicht' => false,
            'optionen' => [
                'shop' => ['label' => 'Online-Shop', 'leistung' => 'shop-integration'],
                'blog' => ['label' => 'Blog oder Neuigkeiten', 'leistung' => 'blog-system'],
            ],
        ];

        $ziele = [
            'name' => 'ziele',
            'typ' => 'mehrfach',
            'frage' => 'Was soll die Website erreichen?',
            'pflicht' => true,
            'optionen' => [
                'informieren' => ['label' => 'Informieren'],
                'anfragen' => ['label' => 'Anfragen gewinnen'],
                'verkaufen' => ['label' => 'Produkte oder Leistungen verkaufen'],
                'bewerber' => ['label' => 'Bewerberinnen und Bewerber finden'],
                'vorstellen' => ['label' => 'Mich oder uns vorstellen'],
            ],
        ];

        $inhalte = [
            'name' => 'inhalte',
            'typ' => 'auswahl',
            'frage' => 'Gibt es schon Texte, Bilder und ein Logo?',
            'pflicht' => true,
            'optionen' => [
                'vorhanden' => ['label' => 'Ja, alles da'],
                'teilweise' => ['label' => 'Teilweise'],
                'fehlt' => ['label' => 'Nein, da brauche ich Hilfe'],
            ],
        ];

        return match ($vorhaben) {
            'website-neu' => [
                'ueberschrift' => 'Deine neue Website',
                'text' => 'Vier kurze Fragen, damit wir wissen, wie groß das Ganze wird.',
                'felder' => [$umfang, $ziele, $erweiterungen, $inhalte],
            ],
            'website-ueberarbeiten' => [
                'ueberschrift' => 'Deine bestehende Website',
                'text' => 'Zeig uns, worum es geht, und was sich ändern soll.',
                'felder' => [
                    ['name' => 'adresse', 'typ' => 'text', 'frage' => 'Adresse der aktuellen Website', 'pflicht' => true, 'platzhalter' => 'www.beispiel.de'],
                    [
                        'name' => 'aendern',
                        'typ' => 'mehrfach',
                        'frage' => 'Was soll sich ändern?',
                        'pflicht' => true,
                        'optionen' => [
                            'design' => ['label' => 'Gestaltung'],
                            'texte' => ['label' => 'Texte'],
                            'bilder' => ['label' => 'Bilder'],
                            'mobil' => ['label' => 'Darstellung auf dem Handy'],
                            'seiten' => ['label' => 'Neue Seiten oder Bereiche'],
                            'technik' => ['label' => 'Technik und Geschwindigkeit'],
                        ],
                    ],
                    array_merge($umfang, ['frage' => 'Wie umfangreich soll sie danach sein?']),
                    $erweiterungen,
                ],
            ],
            'webanwendung' => [
                'ueberschrift' => 'Deine Webanwendung',
                'text' => 'Je genauer du beschreibst, was sie können soll, desto belastbarer wird unsere Einschätzung.',
                'felder' => [
                    [
                        'name' => 'nutzer',
                        'typ' => 'auswahl',
                        'frage' => 'Wer arbeitet damit?',
                        'pflicht' => true,
                        'optionen' => [
                            'intern' => ['label' => 'Nur wir im Betrieb'],
                            'kunden' => ['label' => 'Unsere Kundinnen und Kunden'],
                            'beide' => ['label' => 'Beide'],
                        ],
                    ],
                    [
                        'name' => 'funktionen',
                        'typ' => 'mehrfach',
                        'frage' => 'Was soll sie können?',
                        'pflicht' => true,
                        'optionen' => [
                            'anmeldung' => ['label' => 'Anmeldung und Benutzerkonten'],
                            'termine' => ['label' => 'Buchungen oder Termine'],
                            'zahlung' => ['label' => 'Bezahlen'],
                            'verwaltung' => ['label' => 'Daten erfassen und verwalten'],
                            'schnittstellen' => ['label' => 'Anbindung an andere Systeme'],
                        ],
                    ],
                    ['name' => 'beschreibung', 'typ' => 'absatz', 'frage' => 'Beschreib kurz, was die Anwendung tun soll', 'pflicht' => true, 'platzhalter' => 'Zum Beispiel: Kunden sollen Termine buchen und ihre Unterlagen hochladen können.'],
                ],
            ],
            'ki' => [
                'ueberschrift' => 'Deine KI-Automatisierung',
                'text' => 'Was nimmt dir heute Zeit, das eine Maschine erledigen könnte?',
                'felder' => [
                    [
                        'name' => 'bereich',
                        'typ' => 'auswahl',
                        'frage' => 'Worum geht es am ehesten?',
                        'pflicht' => true,
                        'optionen' => [
                            'workflow' => ['label' => 'Abläufe automatisieren', 'text' => 'Wiederkehrende Schritte laufen von allein.', 'leistung' => 'ki-workflow'],
                            'chatbot' => ['label' => 'Chatbot für Website oder Support', 'text' => 'Beantwortet Fragen, bevor sie bei dir landen.', 'leistung' => 'ki-chatbot'],
                            'dokumente' => ['label' => 'Dokumente und E-Mails', 'text' => 'Sortieren, auslesen, vorformulieren.', 'leistung' => 'ki-dokumente'],
                            'eigenes' => ['label' => 'Etwas Eigenes', 'text' => 'Passt in keine Schublade.', 'leistung' => 'ki-individuell'],
                        ],
                    ],
                    ['name' => 'werkzeuge', 'typ' => 'text', 'frage' => 'Womit arbeitest du heute?', 'pflicht' => false, 'platzhalter' => 'z. B. Outlook, Excel, ein Branchenprogramm'],
                    ['name' => 'beschreibung', 'typ' => 'absatz', 'frage' => 'Welche Arbeit soll wegfallen?', 'pflicht' => true, 'platzhalter' => 'Zum Beispiel: Anfragen aus dem Postfach sollen automatisch als Aufgabe angelegt werden.'],
                ],
            ],
            default => [
                'ueberschrift' => 'Erzähl uns davon',
                'text' => 'Kein Problem, wenn du noch nicht weißt, was du brauchst. Beschreib einfach, was dich stört oder was du erreichen willst.',
                'felder' => [
                    ['name' => 'beschreibung', 'typ' => 'absatz', 'frage' => 'Worum geht es?', 'pflicht' => true],
                ],
            ],
        };
    }

    /**
     * Prüfregeln eines Schritts – aus derselben Liste abgeleitet, die auch die
     * Seite zeichnet. Eine Antwort, die es als Option nicht gibt, kommt so
     * nicht durch.
     *
     * @return array<string, list<mixed>>
     */
    public static function regeln(string $schritt, array $antworten = []): array
    {
        $regeln = [];

        foreach (self::schritt($schritt, $antworten)['felder'] as $feld) {
            $pflicht = $feld['pflicht'] ? 'required' : 'nullable';

            $regeln[$feld['name']] = match ($feld['typ']) {
                'auswahl' => [$pflicht, Rule::in(array_keys($feld['optionen']))],
                'mehrfach' => [$pflicht, 'array'],
                'email' => [$pflicht, 'email:rfc', 'max:180'],
                'absatz' => [$pflicht, 'string', 'max:3000'],
                'haken' => $feld['pflicht'] ? ['accepted'] : ['nullable'],
                default => [$pflicht, 'string', 'max:180'],
            };

            if ($feld['typ'] === 'mehrfach') {
                $regeln[$feld['name'].'.*'] = [Rule::in(array_keys($feld['optionen']))];
            }
        }

        return $regeln;
    }

    /** @return array<string, string> Feldname => Frage, für lesbare Fehlermeldungen */
    public static function bezeichnungen(string $schritt, array $antworten = []): array
    {
        return collect(self::schritt($schritt, $antworten)['felder'])
            ->mapWithKeys(fn ($feld) => [$feld['name'] => '„'.$feld['frage'].'“'])
            ->all();
    }

    /**
     * Welche Leistungen aus der Preisliste zu den Antworten gehören.
     *
     * @return list<string> slugs aus services, die Hauptleistung zuerst
     */
    public static function leistungen(array $antworten): array
    {
        if (($antworten['vorhaben'] ?? '') === 'webanwendung') {
            return ['extended-website'];
        }

        $slugs = [];

        foreach (self::schritt('details', $antworten)['felder'] as $feld) {
            foreach ((array) ($antworten[$feld['name']] ?? []) as $wert) {
                if ($slug = $feld['optionen'][$wert]['leistung'] ?? null) {
                    $slugs[] = $slug;
                }
            }
        }

        return array_values(array_unique($slugs));
    }

    /**
     * Der Richtpreis.
     *
     * Bewusst ein „ab"-Preis und kein Angebot: er addiert die Listenpreise der
     * gewählten Leistungen und weiß nichts von dem, was im Gespräch noch
     * dazukommt. Ohne passende Leistung (Vorhaben „unsicher") gibt es keinen
     * Preis – eine erfundene Zahl wäre schlechter als die ehrliche Auskunft,
     * dass wir erst reden müssen.
     *
     * @return array{leistungen: list<string>, festpreis: ?int, monatlich: ?int, einrichtung: ?int, laufzeit: ?int, danach: ?int, zahlweise: string}
     */
    public static function preis(array $antworten): array
    {
        $slugs = self::leistungen($antworten);

        /** @var Collection<int, Service> $leistungen */
        $leistungen = Service::whereIn('slug', $slugs)->get()
            ->sortBy(fn (Service $leistung) => array_search($leistung->slug, $slugs))
            ->values();

        $haupt = $leistungen->first();
        $alleImAbo = $leistungen->isNotEmpty() && $leistungen->every(fn (Service $l) => $l->hatAbo());

        return [
            'leistungen' => $leistungen->pluck('name')->all(),
            'festpreis' => $leistungen->isEmpty() ? null : (int) $leistungen->sum('price'),
            'monatlich' => $alleImAbo ? (int) $leistungen->sum('monthly_price') : null,
            'einrichtung' => $alleImAbo ? (int) $leistungen->sum('setup_fee') : null,
            'laufzeit' => $alleImAbo ? $haupt?->term_months : null,
            'danach' => $alleImAbo ? (int) $leistungen->sum('renewal_price') : null,
            'zahlweise' => $antworten['zahlweise'] ?? 'offen',
        ];
    }

    /**
     * Fragen und Antworten in Klartext – für Mail, Redaktion und die
     * Abschlussseite. Kontaktdaten stehen nicht darin, die haben eigene Felder.
     *
     * @return list<array{frage: string, antwort: string}>
     */
    public static function zusammenfassung(array $antworten): array
    {
        $zeilen = [];

        foreach (['vorhaben', 'details', 'rahmen'] as $schritt) {
            foreach (self::schritt($schritt, $antworten)['felder'] as $feld) {
                $wert = $antworten[$feld['name']] ?? null;

                if ($wert === null || $wert === '' || $wert === []) {
                    continue;
                }

                $antwort = isset($feld['optionen'])
                    ? collect((array) $wert)->map(fn ($w) => $feld['optionen'][$w]['label'] ?? $w)->implode(', ')
                    : (string) $wert;

                $zeilen[] = ['frage' => $feld['frage'], 'antwort' => $antwort];
            }
        }

        return $zeilen;
    }

    /**
     * Der Richtpreis in Worten – eine oder zwei Zeilen, je nach Zahlweise.
     *
     * Hier und nicht im Blade, weil Abschlussseite und beide Mails denselben
     * Satz brauchen. Ein Preis, der in der Mail anders klingt als auf der
     * Seite, ist der Anfang jeder Rückfrage.
     *
     * @param  array<string, mixed>  $preis  Ergebnis von preis()
     * @return list<array{titel: string, betrag: string, zusatz: string}>
     */
    public static function preisZeilen(array $preis): array
    {
        $zeilen = [];

        if ($preis['monatlich'] !== null && $preis['zahlweise'] !== 'festpreis') {
            $zusatz = [];

            if ($preis['einrichtung']) {
                $zusatz[] = 'zuzüglich '.self::euro($preis['einrichtung']).' Einrichtung einmalig';
            }

            if ($preis['laufzeit']) {
                $zusatz[] = $preis['laufzeit'].' Monate Mindestlaufzeit';
            }

            if ($preis['danach'] !== null && $preis['laufzeit']) {
                $zusatz[] = 'danach '.self::euro($preis['danach']).' im Monat, monatlich kündbar';
            }

            $zeilen[] = [
                'titel' => 'Monatlich im Abo',
                'betrag' => 'ab '.self::euro($preis['monatlich']).' im Monat',
                'zusatz' => ucfirst(implode(', ', $zusatz)).'.',
            ];
        }

        if ($preis['festpreis'] !== null && ($preis['zahlweise'] !== 'abo' || $preis['monatlich'] === null)) {
            $zeilen[] = [
                'titel' => 'Einmalig zum Festpreis',
                'betrag' => 'ab '.self::euro($preis['festpreis']),
                'zusatz' => 'Hosting und Pflege kommen beim Festpreis getrennt dazu.',
            ];
        }

        return $zeilen;
    }

    /** Betrag in der Schreibweise der Leistungsseite. */
    public static function euro(int|float $betrag): string
    {
        return number_format((float) $betrag, 0, ',', '.').' €';
    }
}
