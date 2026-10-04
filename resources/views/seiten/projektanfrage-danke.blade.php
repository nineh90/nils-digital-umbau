{{--
    Abschluss des Projektfragebogens: Richtpreis und was jetzt passiert.

    noindex, weil die Seite nur mit einer eben abgeschickten Anfrage etwas
    zeigt. Der Preis kommt aus dem Datensatz der Anfrage und nicht frisch aus
    der Preisliste – was hier steht, ist genau das, was auch in der Mail steht.
--}}

<x-layouts.oeffentlich
    titel="Deine Projektanfrage ist angekommen"
    beschreibung="Deine Projektanfrage ist bei Nils-Digital angekommen."
    robots="noindex, follow">

    <x-seitenkopf
        ueberschrift="Danke, {{ $anfrage->name }}"
        text="Deine Anfrage ist angekommen. Wir melden uns innerhalb von 24 Stunden persönlich bei dir – eine Bestätigung ist unterwegs an {{ $anfrage->email }}." />

    <div class="mx-auto max-w-3xl px-5 py-14">

        <section aria-labelledby="richtpreis" role="status">
            <p class="font-mono text-xs uppercase tracking-widest text-akzent">Dein Richtpreis</p>

            @if ($preisZeilen !== [])
                <h2 id="richtpreis" class="sr-only">Dein Richtpreis</h2>

                <div @class(['mt-4 grid gap-5', 'sm:grid-cols-2' => count($preisZeilen) > 1])>
                    @foreach ($preisZeilen as $zeile)
                        <div class="rounded-2xl border border-akzent/40 bg-karte p-6">
                            <p class="text-sm text-text-leise">{{ $zeile['titel'] }}</p>
                            <p class="mt-2 font-display text-3xl text-akzent">{{ $zeile['betrag'] }}</p>
                            <p class="mt-3 text-sm text-text-leise">{{ $zeile['zusatz'] }}</p>
                        </div>
                    @endforeach
                </div>

                @if ($anfrage->details['preis']['leistungen'] !== [])
                    <p class="mt-5 text-sm text-text-leise">
                        Gerechnet mit: {{ implode(' + ', $anfrage->details['preis']['leistungen']) }}.
                    </p>
                @endif

                <p class="mt-3 text-sm text-text-leise">
                    Das ist ein unverbindlicher Richtwert auf Grundlage deiner Angaben. Das verbindliche
                    Angebot bekommst du von uns, sobald wir dein Vorhaben kennen. Es gilt die
                    Kleinunternehmerregelung nach § 19 UStG, die Preise enthalten daher keine Umsatzsteuer.
                </p>
            @else
                <h2 id="richtpreis" class="mt-3 text-2xl">Den nennen wir dir im Gespräch</h2>
                <p class="mt-3 text-text-leise">
                    Für dein Vorhaben können wir noch keine Zahl nennen – dafür müssen wir erst verstehen,
                    worum es geht. Eine erfundene Zahl würde dir nicht helfen.
                </p>
            @endif
        </section>

        <section class="mt-12" aria-labelledby="angaben">
            <h2 id="angaben" class="text-xl">Deine Angaben</h2>

            <dl class="mt-5 divide-y divide-linie border-y border-linie">
                @foreach ($zusammenfassung as $zeile)
                    <div class="grid gap-1 py-3 sm:grid-cols-[14rem_1fr] sm:gap-6">
                        <dt class="text-sm text-text-leise">{{ $zeile['frage'] }}</dt>
                        <dd>{{ $zeile['antwort'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <div class="mt-12 flex flex-wrap gap-4">
            <a href="{{ route('projekte') }}"
               class="rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                Unsere Projekte ansehen
            </a>
            <a href="{{ route('termine') }}"
               class="rounded-lg border border-linie px-6 py-3 transition-colors hover:border-akzent hover:text-akzent">
                Gleich einen Termin buchen
            </a>
        </div>
    </div>

</x-layouts.oeffentlich>
