@php
    $bereiche = [
        ['🤖', 'KI-Automatisierung', 'Wiederkehrende Prozesse laufen von selbst. Formulare, E-Mails, Datenübertragungen – damit ihr euch um euer Kerngeschäft kümmern könnt.'],
        ['🌐', 'Web- & App-Entwicklung', 'Websites und individuelle Anwendungen: schnell, auf jedem Gerät benutzbar und von Anfang an für die Suche gebaut.'],
        ['🎯', 'Individuelle Lösungen', 'Keine Stangenware. Was ihr bekommt, ist auf euren Betrieb zugeschnitten – und ihr arbeitet direkt mit uns.'],
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

    if ($stimmen->isNotEmpty()) {
        $jsonld['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => round($stimmen->avg('rating'), 1),
            'reviewCount' => $stimmen->count(),
        ];
    }
@endphp

<x-layouts.oeffentlich
    titel="Nils-Digital"
    beschreibung="KI-Automatisierung, Webentwicklung und individuelle Apps für kleine Unternehmen und Selbstständige – deutschlandweit und im Raum Münster, Osnabrück und Ibbenbüren."
    :jsonld="$jsonld">

    {{--
        Hero.

        Platzhalter-Hintergrund bis das echte Motiv da ist: ein Verlauf statt
        eines Stockfotos. Sobald ein Bild oder Video vorliegt, wird hier nur die
        Quelle getauscht – der Aufbau bleibt.
    --}}
    <section class="relative overflow-hidden border-b border-linie">
        <div aria-hidden="true"
             class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(0,188,212,0.18),transparent_55%),radial-gradient(ellipse_at_bottom_left,rgba(0,188,212,0.10),transparent_50%)]"></div>

        <div class="relative mx-auto max-w-6xl px-5 py-24 sm:py-32">
            <p class="text-sm tracking-widest text-akzent uppercase">
                Digitale Lösungen für Unternehmen &amp; Selbstständige
            </p>

            <h1 class="mt-5 max-w-3xl text-4xl leading-[1.1] sm:text-6xl">
                Digitale Lösungen,<br>
                die für euch <span class="text-akzent">arbeiten</span>
            </h1>

            <p class="mt-6 max-w-xl text-lg leading-relaxed text-text-leise">
                KI-Automatisierung, Webentwicklung und individuelle Apps.
                Ihr arbeitet direkt mit uns – feste Ansprechpartner, kurze Wege,
                kein anonymes Support-Team.
            </p>

            <div class="mt-9 flex flex-wrap gap-3">
                <a href="{{ route('kontakt') }}"
                   class="rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                    Kostenlos anfragen
                </a>
                <a href="{{ route('leistungen') }}"
                   class="rounded-lg border border-linie px-6 py-3 transition-colors hover:border-akzent hover:text-akzent">
                    Leistungen ansehen
                </a>
            </div>
        </div>
    </section>

    <div class="mx-auto max-w-6xl px-5">

        <section class="py-20" aria-labelledby="bereiche">
            <h2 id="bereiche" class="text-2xl sm:text-3xl">Was wir machen</h2>
            <p class="mt-3 max-w-2xl text-text-leise">
                Drei Bereiche, alles aus einer Hand.
            </p>

            <div class="mt-10 grid gap-5 sm:grid-cols-3">
                @foreach ($bereiche as [$symbol, $titel, $text])
                    <article class="rounded-2xl border border-linie bg-karte p-6">
                        <span class="text-3xl" aria-hidden="true">{{ $symbol }}</span>
                        <h3 class="mt-4 text-lg">{{ $titel }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-text-leise">{{ $text }}</p>
                    </article>
                @endforeach
            </div>

            <a href="{{ route('leistungen') }}" class="mt-8 inline-block text-akzent hover:underline">
                Alle Leistungen und Preise →
            </a>
        </section>

        @if ($projekte->isNotEmpty())
            {{-- Aus der Datenbank, nicht mehr doppelt gepflegt: auf der alten
                 Seite standen diese Karten hart in index.html und zusätzlich in
                 projects.json – Startseite und Projektseite liefen deshalb
                 zwangsläufig auseinander. --}}
            <section class="border-t border-linie py-20" aria-labelledby="referenzen">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <h2 id="referenzen" class="text-2xl sm:text-3xl">Referenzen</h2>
                    <a href="{{ route('projekte') }}" class="text-akzent hover:underline">Alle Projekte →</a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projekte as $projekt)
                        <x-projektkachel :projekt="$projekt" />
                    @endforeach
                </div>
            </section>
        @endif

        @if ($stimmen->isNotEmpty())
            <section class="border-t border-linie py-20" aria-labelledby="stimmen">
                <h2 id="stimmen" class="text-2xl sm:text-3xl">Was Kunden sagen</h2>

                <div class="mt-10 grid gap-5 sm:grid-cols-2">
                    @foreach ($stimmen as $stimme)
                        <figure class="rounded-2xl border border-linie bg-karte p-6">
                            @if ($stimme->rating)
                                <p class="text-akzent" aria-label="{{ $stimme->rating }} von 5 Sternen">
                                    <span aria-hidden="true">{{ str_repeat('★', $stimme->rating) }}</span>
                                </p>
                            @endif
                            <blockquote class="mt-3 leading-relaxed text-text-leise">
                                {{ $stimme->text }}
                            </blockquote>
                            <figcaption class="mt-4 text-sm">
                                {{ $stimme->name }}
                                @if ($stimme->project)
                                    <span class="text-text-leise">· {{ $stimme->project }}</span>
                                @endif
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($beitraege->isNotEmpty())
            <section class="border-t border-linie py-20" aria-labelledby="aktuelles">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <h2 id="aktuelles" class="text-2xl sm:text-3xl">Aus dem Blog</h2>
                    <a href="{{ route('blog.index') }}" class="text-akzent hover:underline">Alle Beiträge →</a>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($beitraege as $beitrag)
                        <x-beitragskachel :beitrag="$beitrag" />
                    @endforeach
                </div>
            </section>
        @endif

        <section class="border-t border-linie py-20">
            <div class="rounded-2xl border border-akzent/30 bg-flaeche-2 p-10 text-center">
                <h2 class="text-2xl sm:text-3xl">Erzählt uns von eurem Vorhaben</h2>
                <p class="mx-auto mt-4 max-w-xl text-text-leise">
                    Ein Gespräch kostet nichts und bringt meist mehr als drei Angebote.
                    Wir sagen euch ehrlich, was geht, was es kostet und was wir nicht machen würden.
                </p>
                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('kontakt') }}"
                       class="rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                        Anfrage schreiben
                    </a>
                    <a href="{{ route('termine') }}"
                       class="rounded-lg border border-linie px-6 py-3 transition-colors hover:border-akzent hover:text-akzent">
                        Termin buchen
                    </a>
                </div>
            </div>
        </section>

    </div>

</x-layouts.oeffentlich>
