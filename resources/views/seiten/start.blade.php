@php
    $bereiche = [
        ['chip', 'KI-Automatisierung', 'Wiederkehrende Prozesse laufen von selbst. Formulare, E-Mails, Datenübertragungen – damit ihr euch um euer Kerngeschäft kümmern könnt.'],
        ['globus', 'Web- & App-Entwicklung', 'Websites und individuelle Anwendungen: schnell, auf jedem Gerät benutzbar und von Anfang an für die Suche gebaut.'],
        ['ziel', 'Individuelle Lösungen', 'Keine Stangenware. Was ihr bekommt, ist auf euren Betrieb zugeschnitten – und ihr arbeitet direkt mit uns.'],
    ];

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => 'Nils-Digital',
        'url' => url('/'),
        'email' => 'info@nils-digital.de',
        'description' => 'KI-Automatisierung, Webentwicklung und individuelle App-Entwicklung für kleine Unternehmen und Selbstständige.',
        'founder' => ['@type' => 'Person', 'name' => 'Nils Nehring'],
        'areaServed' => collect(['Deutschland', 'Münster', 'Osnabrück', 'Ibbenbüren', 'Lengerich', 'Ladbergen'])
            ->map(fn ($o) => ['@type' => 'Place', 'name' => $o])->all(),
        'serviceType' => ['Webdesign', 'Webentwicklung', 'App-Entwicklung', 'KI-Automatisierung'],
        'priceRange' => 'ab 199 €',
    ];

    /*
     * Die Bewertung zählt alle sichtbaren Stimmen, nicht nur die vier gerade
     * gezeigten – sonst schwankte reviewCount bei jedem Aufruf.
     */
    if ($stimmenGesamt->isNotEmpty()) {
        $jsonld['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => round($stimmenGesamt->avg('rating'), 1),
            'reviewCount' => $stimmenGesamt->count(),
        ];
    }
@endphp

<x-layouts.oeffentlich
    titel="Nils-Digital"
    beschreibung="KI-Automatisierung, Webentwicklung und individuelle Apps für kleine Unternehmen und Selbstständige – deutschlandweit und im Raum Münster, Osnabrück und Ibbenbüren."
    :jsonld="$jsonld">

    {{--
        Hero.

        Rechts stehen echte Projekt-Screenshots in Browser-Rahmen statt eines
        Stockfotos oder Platzhalters. Das ist der stärkste Inhalt, den die
        Startseite hat: wer hier landet, fragt sich "kann der bauen, was ich
        brauche?" – und ein laufendes Kundenprojekt beantwortet das besser als
        jedes Motiv. Welche Projekte erscheinen, wechselt bei jedem Aufruf.
    --}}
    <section class="relative overflow-hidden border-b border-linie">

        {{-- Zusätzlicher Lichtschein nur hier. Die durchlaufende
             Hintergrundebene liegt bewusst schwächer; der Hero darf heller
             sein, weil danach nichts mehr um Aufmerksamkeit konkurriert. --}}
        <div aria-hidden="true"
             class="absolute inset-0 bg-[radial-gradient(ellipse_at_70%_20%,rgba(0,188,212,0.13),transparent_60%)]"></div>

        <div class="relative mx-auto max-w-6xl px-5 py-20 sm:py-24 lg:py-28">
            <div class="grid items-center gap-14 lg:grid-cols-[1.05fr_0.95fr]">

                {{-- Der Hero trägt bewusst kein data-auftritt.

                     Alles über der Falz muss sofort dastehen: wer die Seite
                     öffnet, soll lesen können, nicht auf eine Animation warten.
                     Und liefe das Skript einmal nicht, wäre ausgerechnet das
                     Erste, was jemand sieht, eine leere Fläche. Eingeblendet
                     wird erst, was beim Scrollen dazukommt. --}}
                <div>
                    <p class="font-mono text-xs tracking-[0.2em] text-akzent uppercase">
                        Digitale Lösungen für Unternehmen &amp; Selbstständige
                    </p>

                    <h1 class="mt-5 text-4xl leading-[1.08] sm:text-5xl lg:text-6xl">
                        Digitale Lösungen,<br>
                        die für euch <span class="text-akzent">arbeiten</span>
                    </h1>

                    <p class="mt-6 max-w-lg text-lg leading-relaxed text-text-leise">
                        KI-Automatisierung, Webentwicklung und individuelle Apps.
                        Ihr arbeitet direkt mit uns – feste Ansprechpartner, kurze Wege,
                        kein anonymes Support-Team.
                    </p>

                    <div class="mt-9 flex flex-wrap gap-3">
                        <a href="{{ route('kontakt') }}"
                           class="rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-all hover:bg-akzent-hell hover:shadow-lg hover:shadow-akzent/25">
                            Kostenlos anfragen
                        </a>
                        <a href="{{ route('leistungen') }}"
                           class="rounded-lg border border-linie px-6 py-3 transition-colors hover:border-akzent hover:text-akzent">
                            Leistungen ansehen
                        </a>
                    </div>

                    {{-- Vertrauenszeile aus echten Zahlen. Steht bewusst direkt
                         unter den Schaltflächen: genau dort entscheidet sich,
                         ob jemand klickt. --}}
                    @if ($stimmenGesamt->isNotEmpty())
                        <div class="mt-8 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
                            <span class="text-akzent" aria-hidden="true">{{ str_repeat('★', 5) }}</span>
                            <span class="font-mono text-text-leise">
                                {{ number_format($stimmenGesamt->avg('rating'), 1, ',', '') }}/5
                            </span>
                            <span class="text-text-leise">
                                aus {{ $stimmenGesamt->count() }}
                                {{ $stimmenGesamt->count() === 1 ? 'Bewertung' : 'Bewertungen' }}
                            </span>
                        </div>
                    @endif
                </div>

                @if ($heldenprojekte->isNotEmpty())
                    <div class="relative">
                        <div aria-hidden="true"
                             class="absolute -inset-8 -z-10 bg-[radial-gradient(circle,rgba(0,188,212,0.18),transparent_65%)]"></div>

                        {{-- Die beiden Fenster sind je 88% breit und das
                             zweite um 12% eingerückt: zusammen genau 100%. So
                             überlappen sie sichtbar, ohne aus der Spalte zu
                             laufen. --}}
                        <div class="mx-auto max-w-lg">
                            <div class="w-[88%]">
                                <x-projektfenster :projekt="$heldenprojekte[0]" neigung="-2" sofort />
                            </div>

                            {{-- Das zweite Fenster macht aus einem Bild eine
                                 Auswahl. Auf schmalen Geräten fällt es weg:
                                 dort stünden zwei versetzte Fenster
                                 übereinander nur im Weg. --}}
                            @if ($heldenprojekte->count() > 1)
                                <div class="relative -mt-14 ml-[12%] hidden w-[88%] sm:block">
                                    <x-projektfenster :projekt="$heldenprojekte[1]" neigung="2" />
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>

    <div class="mx-auto max-w-6xl px-5">

        <section class="py-20 sm:py-24" aria-labelledby="bereiche">
            <div data-auftritt="0">
                <p class="font-mono text-xs tracking-[0.2em] text-akzent uppercase">Leistungen</p>
                <h2 id="bereiche" class="mt-3 text-2xl sm:text-3xl">Was wir machen</h2>
                <p class="mt-3 max-w-2xl text-text-leise">
                    Drei Bereiche, alles aus einer Hand.
                </p>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-3">
                @foreach ($bereiche as $i => [$symbol, $titel, $text])
                    <article data-auftritt="{{ $i }}"
                             class="group rounded-2xl border border-linie bg-karte/70 p-6 transition-all duration-300 hover:-translate-y-1 hover:border-akzent/40 hover:bg-karte hover:shadow-xl hover:shadow-black/30">
                        <span class="inline-flex rounded-xl border border-linie bg-flaeche-2 p-3 text-akzent transition-colors group-hover:border-akzent/40">
                            <x-symbol :name="$symbol" />
                        </span>
                        <h3 class="mt-4 text-lg">{{ $titel }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-text-leise">{{ $text }}</p>
                    </article>
                @endforeach
            </div>

            <a href="{{ route('leistungen') }}"
               class="group mt-8 inline-flex items-center gap-2 text-akzent hover:underline">
                Alle Leistungen und Preise
                <x-symbol name="pfeil-rechts" klasse="h-4 w-4 transition-transform group-hover:translate-x-1" />
            </a>
        </section>

        @if ($projekte->isNotEmpty())
            {{-- Aus der Datenbank, nicht mehr doppelt gepflegt: auf der alten
                 Seite standen diese Karten hart in index.html und zusätzlich in
                 projects.json – Startseite und Projektseite liefen deshalb
                 zwangsläufig auseinander.

                 Hier bewusst *keine* Zufallsauswahl wie bei den Stimmen: wer
                 die Referenzen liest, prüft Bandbreite. Ein wechselndes
                 Einzelprojekt beantwortet das nicht, und Suchmaschinen sähen
                 bei jedem Besuch eine andere Seite. Das Wechselnde steht oben
                 im Hero. --}}
            <section class="border-t border-linie py-20 sm:py-24" aria-labelledby="referenzen">
                <div class="flex flex-wrap items-end justify-between gap-4" data-auftritt="0">
                    <div>
                        <p class="font-mono text-xs tracking-[0.2em] text-akzent uppercase">Referenzen</p>
                        <h2 id="referenzen" class="mt-3 text-2xl sm:text-3xl">Was wir gebaut haben</h2>
                    </div>
                    <a href="{{ route('projekte') }}"
                       class="group inline-flex items-center gap-2 text-akzent hover:underline">
                        Alle Projekte
                        <x-symbol name="pfeil-rechts" klasse="h-4 w-4 transition-transform group-hover:translate-x-1" />
                    </a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projekte as $i => $projekt)
                        <div data-auftritt="{{ $i }}">
                            <x-projektkachel :projekt="$projekt" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($stimmen->isNotEmpty())
            <section class="border-t border-linie py-20 sm:py-24" aria-labelledby="stimmen">
                <div data-auftritt="0">
                    <p class="font-mono text-xs tracking-[0.2em] text-akzent uppercase">Kundenstimmen</p>
                    <h2 id="stimmen" class="mt-3 text-2xl sm:text-3xl">Was Kunden sagen</h2>
                </div>

                <div class="mt-10 grid gap-5 sm:grid-cols-2">
                    @foreach ($stimmen as $i => $stimme)
                        <figure data-auftritt="{{ $i }}"
                                class="relative flex flex-col rounded-2xl border border-linie bg-karte/70 p-6 transition-colors hover:border-akzent/30">
                            @if ($stimme->rating)
                                <p class="text-akzent" aria-label="{{ $stimme->rating }} von 5 Sternen">
                                    <span aria-hidden="true">{{ str_repeat('★', $stimme->rating) }}</span>
                                </p>
                            @endif

                            <blockquote class="mt-3 flex-1 leading-relaxed text-text-leise">
                                {{ $stimme->text }}
                            </blockquote>

                            <figcaption class="mt-5 flex flex-wrap items-center gap-x-2 border-t border-linie pt-4 text-sm">
                                <span class="font-medium">{{ $stimme->name }}</span>
                                @if ($stimme->project)
                                    <span class="font-mono text-xs text-text-leise">{{ $stimme->project }}</span>
                                @endif
                                @if ($stimme->source)
                                    <span class="ml-auto font-mono text-xs text-text-leise">via {{ $stimme->source }}</span>
                                @endif
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($beitraege->isNotEmpty())
            <section class="border-t border-linie py-20 sm:py-24" aria-labelledby="aktuelles">
                <div class="flex flex-wrap items-end justify-between gap-4" data-auftritt="0">
                    <div>
                        <p class="font-mono text-xs tracking-[0.2em] text-akzent uppercase">Blog</p>
                        <h2 id="aktuelles" class="mt-3 text-2xl sm:text-3xl">Aus der Werkstatt</h2>
                    </div>
                    <a href="{{ route('blog.index') }}"
                       class="group inline-flex items-center gap-2 text-akzent hover:underline">
                        Alle Beiträge
                        <x-symbol name="pfeil-rechts" klasse="h-4 w-4 transition-transform group-hover:translate-x-1" />
                    </a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($beitraege as $i => $beitrag)
                        <div data-auftritt="{{ $i }}">
                            <x-beitragskachel :beitrag="$beitrag" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="border-t border-linie py-20 sm:py-24">
            <div data-auftritt="0"
                 class="relative overflow-hidden rounded-2xl border border-akzent/25 bg-flaeche-2/80 p-10 text-center sm:p-14">
                <div aria-hidden="true"
                     class="absolute inset-0 bg-[radial-gradient(ellipse_at_50%_0%,rgba(0,188,212,0.14),transparent_65%)]"></div>

                <div class="relative">
                    <h2 class="text-2xl sm:text-3xl">Erzählt uns von eurem Vorhaben</h2>
                    <p class="mx-auto mt-4 max-w-xl leading-relaxed text-text-leise">
                        Ein Gespräch kostet nichts und bringt meist mehr als drei Angebote.
                        Wir sagen euch ehrlich, was geht, was es kostet und was wir nicht machen würden.
                    </p>
                    <div class="mt-8 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('kontakt') }}"
                           class="rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-all hover:bg-akzent-hell hover:shadow-lg hover:shadow-akzent/25">
                            Anfrage schreiben
                        </a>
                        <a href="{{ route('termine') }}"
                           class="rounded-lg border border-linie px-6 py-3 transition-colors hover:border-akzent hover:text-akzent">
                            Termin buchen
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </div>

</x-layouts.oeffentlich>
