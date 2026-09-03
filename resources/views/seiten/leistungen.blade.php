@php
    $angebote = $gruppen->flatMap->services->map(fn ($l) => [
        '@type' => 'Offer',
        'name' => $l->name,
        'description' => $l->description,
        'price' => $l->price,
        'priceCurrency' => 'EUR',
        'category' => $l->category->name,
    ])->all();

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'Leistungen von Nils-Digital',
        'provider' => ['@type' => 'Organization', 'name' => 'Nils-Digital', 'url' => url('/')],
        'areaServed' => ['@type' => 'Country', 'name' => 'Deutschland'],
        'url' => route('leistungen'),
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => 'Leistungen und Preise',
            'itemListElement' => $angebote,
        ],
    ];
@endphp

<x-layouts.oeffentlich
    titel="Leistungen und Preise"
    beschreibung="Webentwicklung, KI-Automatisierung, Hosting und Pflege – modular aufgebaut, transparent bepreist. Alle Preise auf einen Blick."
    :jsonld="$jsonld">

    <x-seitenkopf
        ueberschrift="Leistungen und Preise"
        text="Modular aufgebaut, transparent bepreist. Du zahlst für das, was du brauchst – nicht für ein Paket, in dem die Hälfte ungenutzt bleibt.">
        <x-slot:aktionen>
            <a href="{{ route('kontakt') }}"
               class="rounded-lg bg-akzent px-5 py-2.5 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                Unverbindlich anfragen
            </a>
            @if (\Illuminate\Support\Facades\Route::has('termine'))
                <a href="{{ route('termine') }}"
                   class="rounded-lg border border-linie px-5 py-2.5 transition-colors hover:border-akzent hover:text-akzent">
                    Beratung buchen
                </a>
            @endif
        </x-slot:aktionen>
    </x-seitenkopf>

    <div class="mx-auto max-w-6xl px-5 py-14">

        @foreach ($gruppen as $gruppe)
            <section class="mb-14" aria-labelledby="gruppe-{{ $gruppe->slug }}">
                <h2 id="gruppe-{{ $gruppe->slug }}" class="mb-6 text-2xl" data-auftritt="0">{{ $gruppe->name }}</h2>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($gruppe->services as $i => $leistung)
                        <article class="flex flex-col rounded-2xl border border-linie bg-karte p-6 transition-colors hover:border-akzent/40"
                                 data-auftritt="{{ $i }}">
                            @if ($leistung->icon)
                                <span class="mb-3 text-2xl" aria-hidden="true">{{ $leistung->icon }}</span>
                            @endif

                            <h3 class="text-lg">{{ $leistung->name }}</h3>

                            <p class="mt-2 flex-1 text-sm leading-relaxed text-text-leise">
                                {{ $leistung->description }}
                            </p>

                            <p class="mt-5 font-display text-xl text-akzent">
                                {{ $leistung->priceLabel() ?? 'auf Anfrage' }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach

        {{-- Inhaltlich unverändert von der alten Leistungsseite übernommen:
             die Aussage zur Inhaberschaft der Zugänge ist eine Zusage an den
             Kunden und darf sich beim Umbau nicht still verändern. --}}
        <section class="rounded-2xl border border-linie bg-karte p-8" data-auftritt="0">
            <h2 class="text-xl">Hosting und Pflege</h2>
            <p class="mt-4 leading-relaxed text-text-leise">
                Domain und Hosting werden <strong class="text-text">im Auftrag des Kunden</strong>
                bei einem Anbieter wie STRATO gebucht. Der Kunde bleibt jederzeit
                <strong class="text-text">rechtlicher Inhaber aller Zugänge</strong>.
                Wir übernehmen die technische Einrichtung, Verwaltung und laufende Pflege –
                damit du dich um nichts kümmern musst.
            </p>
            <p class="mt-4 leading-relaxed text-text-leise">
                Mit einem Pflegeabo bleibt deine Website technisch aktuell und du hast
                jederzeit einen festen Ansprechpartner für Änderungen, Optimierungen oder Fragen.
            </p>
        </section>

        <section class="mt-14 rounded-2xl border border-akzent/30 bg-flaeche-2 p-8 text-center"
                 data-auftritt="0" data-aus="naeher">
            <h2 class="text-xl">Nichts Passendes dabei?</h2>
            <p class="mx-auto mt-3 max-w-xl text-text-leise">
                Die meisten Projekte sind eine Mischung. Schreib uns, was du vorhast –
                wir sagen dir ehrlich, was es kostet und ob wir die Richtigen dafür sind.
            </p>
            <a href="{{ route('kontakt') }}"
               class="mt-6 inline-block rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                Projekt beschreiben
            </a>
        </section>

    </div>

</x-layouts.oeffentlich>
