@php
    use App\Support\Fragebogen;

    /*
     * Nur der Einstieg wird indexiert. Die Folgeschritte sind ohne die
     * Antworten davor leer – als Suchtreffer wären sie eine Sackgasse.
     */
    $erster = $nummer === 1;
    $feldKlasse = 'w-full rounded-lg border bg-flaeche-2 px-4 py-2.5 outline-none focus:border-akzent';
    $wert = fn (string $name) => old($name, $antworten[$name] ?? null);
@endphp

<x-layouts.oeffentlich
    titel="Projektanfrage – Website erstellen lassen"
    beschreibung="Vier kurze Schritte zu deinem Projekt: Du beantwortest ein paar Fragen und siehst sofort einen Richtpreis. Wir melden uns innerhalb von 24 Stunden."
    :kanonisch="route('projektanfrage')"
    :robots="$erster ? null : 'noindex, follow'">

    <x-seitenkopf
        ueberschrift="Projektanfrage"
        text="Ein paar Fragen zu deinem Vorhaben – am Ende siehst du sofort, womit du rechnen kannst. Wir melden uns innerhalb von 24 Stunden persönlich." />

    <div class="mx-auto max-w-3xl px-5 py-14">

        {{-- Wo man steht. Eine Liste und kein Balken: Screenreader lesen
             „Schritt 2 von 4" vor, ein Balken sagt ihnen nichts. --}}
        <ol class="mb-10 grid grid-cols-4 gap-2 font-mono text-xs" aria-label="Fortschritt">
            @foreach (Fragebogen::SCHRITTE as $i => $name)
                <li @if ($i + 1 === $nummer) aria-current="step" @endif
                    @class([
                        'border-t-2 pt-2',
                        'border-akzent text-akzent' => $i + 1 <= $nummer,
                        'border-linie text-text-leise' => $i + 1 > $nummer,
                    ])>
                    <span class="sr-only">Schritt</span> {{ $i + 1 }}
                    <span class="hidden sm:inline">· {{ Fragebogen::TITEL[$name] }}</span>
                </li>
            @endforeach
        </ol>

        <h2 class="text-2xl">{{ $inhalt['ueberschrift'] }}</h2>
        <p class="mt-3 text-text-leise">{{ $inhalt['text'] }}</p>

        @if ($errors->any())
            <div role="alert" class="mt-8 rounded-xl border border-red-500/40 bg-red-500/10 p-4">
                <p class="font-medium text-red-200">Da fehlt noch etwas:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-200">
                    @foreach ($errors->all() as $fehler)
                        <li>{{ $fehler }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('projektanfrage.speichern', $schritt) }}" class="mt-8">
            @csrf

            <div class="grid gap-x-5 gap-y-8 sm:grid-cols-2">
                @foreach ($inhalt['felder'] as $feld)
                    @php
                        $name = $feld['name'];
                        $fehler = $errors->has($name);
                        $hilfeId = isset($feld['hilfe']) ? 'hilfe-'.$name : null;
                    @endphp

                    @if (in_array($feld['typ'], ['auswahl', 'mehrfach']))
                        @php
                            $mehrfach = $feld['typ'] === 'mehrfach';
                            $gewaehlt = (array) $wert($name);
                            $mitText = collect($feld['optionen'])->contains(fn ($o) => isset($o['text']));
                        @endphp

                        <fieldset class="sm:col-span-2">
                            <legend class="mb-3 block">
                                {{ $feld['frage'] }}
                                @if ($mehrfach)
                                    <span class="text-sm text-text-leise">– mehrere möglich{{ $feld['pflicht'] ? '' : ', kann leer bleiben' }}</span>
                                @endif
                            </legend>

                            <div @class(['grid gap-3', 'sm:grid-cols-2' => ! $mitText || count($feld['optionen']) > 3])>
                                @foreach ($feld['optionen'] as $schluessel => $option)
                                    {{-- Die ganze Karte ist das label: wer daneben
                                         trifft, trifft trotzdem. has-[:checked]
                                         färbt sie ohne JavaScript, focus-within
                                         zeigt den Fokus der Tastatur. --}}
                                    <label @class([
                                        'flex cursor-pointer gap-3 rounded-xl border bg-karte p-4 transition-colors',
                                        'hover:border-akzent/50 has-[:checked]:border-akzent has-[:checked]:bg-akzent/10',
                                        'focus-within:outline focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-akzent',
                                        'border-red-500/60' => $fehler,
                                        'border-linie' => ! $fehler,
                                    ])>
                                        <input type="{{ $mehrfach ? 'checkbox' : 'radio' }}"
                                               name="{{ $name }}{{ $mehrfach ? '[]' : '' }}"
                                               value="{{ $schluessel }}"
                                               @checked(in_array($schluessel, $gewaehlt, true))
                                               @if (! $mehrfach && $feld['pflicht']) required @endif
                                               class="mt-1 h-4 w-4 shrink-0 accent-akzent">
                                        <span>
                                            <span class="block">{{ $option['label'] }}</span>
                                            @isset($option['text'])
                                                <span class="mt-1 block text-sm text-text-leise">{{ $option['text'] }}</span>
                                            @endisset
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                    @elseif ($feld['typ'] === 'haken')
                        <div class="sm:col-span-2">
                            <label class="flex cursor-pointer gap-3">
                                <input type="checkbox" name="{{ $name }}" value="1"
                                       @checked($wert($name))
                                       @if ($feld['pflicht']) required @endif
                                       @if ($hilfeId) aria-describedby="{{ $hilfeId }}" @endif
                                       class="mt-1 h-4 w-4 shrink-0 accent-akzent">
                                <span>{{ $feld['frage'] }}</span>
                            </label>
                            @if ($hilfeId)
                                <p id="{{ $hilfeId }}" class="mt-2 pl-7 text-sm text-text-leise">{{ $feld['hilfe'] }}</p>
                            @endif
                        </div>

                    @else
                        <div @class(['sm:col-span-2' => empty($feld['halb'])])>
                            <label for="{{ $name }}" class="mb-1.5 block text-sm">
                                {{ $feld['frage'] }}
                                @unless ($feld['pflicht'])
                                    <span class="text-text-leise">– freiwillig</span>
                                @endunless
                            </label>

                            @if ($feld['typ'] === 'absatz')
                                <textarea id="{{ $name }}" name="{{ $name }}" rows="5"
                                          @if ($feld['pflicht']) required @endif
                                          placeholder="{{ $feld['platzhalter'] ?? '' }}"
                                          @class([$feldKlasse, 'border-red-500/60' => $fehler, 'border-linie' => ! $fehler])>{{ $wert($name) }}</textarea>
                            @else
                                <input type="{{ $feld['typ'] }}" id="{{ $name }}" name="{{ $name }}"
                                       value="{{ $wert($name) }}"
                                       @if ($feld['pflicht']) required @endif
                                       @isset($feld['autocomplete']) autocomplete="{{ $feld['autocomplete'] }}" @endisset
                                       @if ($hilfeId) aria-describedby="{{ $hilfeId }}" @endif
                                       placeholder="{{ $feld['platzhalter'] ?? '' }}"
                                       @class([$feldKlasse, 'border-red-500/60' => $fehler, 'border-linie' => ! $fehler])>
                            @endif

                            @if ($hilfeId)
                                <p id="{{ $hilfeId }}" class="mt-1.5 text-xs text-text-leise">{{ $feld['hilfe'] }}</p>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

            @if ($letzter)
                {{-- Honigtopf, wie im Kontaktformular. --}}
                <div class="absolute left-[-9999px]" aria-hidden="true">
                    <label for="website">Website</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>
            @endif

            <div class="mt-10 flex flex-wrap items-center gap-4">
                <button type="submit"
                        class="rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                    {{ $letzter ? 'Anfrage senden und Preis sehen' : 'Weiter' }}
                </button>

                @if ($zurueck)
                    <a href="{{ $zurueck }}"
                       class="text-sm text-text-leise hover:text-akzent hover:underline">Zurück</a>
                @endif

                <span class="ml-auto font-mono text-xs text-text-leise">Schritt {{ $nummer }} von {{ count(Fragebogen::SCHRITTE) }}</span>
            </div>

            @if ($letzter)
                <p class="mt-5 text-xs text-text-leise">
                    Mit dem Absenden stimmst du zu, dass wir deine Angaben zur Bearbeitung
                    deiner Anfrage verwenden. Details in der
                    <a href="{{ route('datenschutz') }}" class="text-akzent hover:underline">Datenschutzerklärung</a>.
                </p>
            @endif
        </form>

        <p class="mt-12 border-t border-linie pt-6 text-center text-sm text-text-leise">
            Lieber formlos? Dann schreib uns einfach über das
            <a href="{{ route('kontakt') }}" class="text-akzent hover:underline">Kontaktformular</a>.
        </p>
    </div>

</x-layouts.oeffentlich>
