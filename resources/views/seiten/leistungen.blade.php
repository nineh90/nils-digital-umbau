@php
    $leistungen = $gruppen->flatMap->services;

    /*
     * Jede Leistung wird einmal als Einmalpreis und – wo es eins gibt – ein
     * zweites Mal als Abo ausgewiesen. Ein Angebot mit zwei Preisen wäre für
     * Suchmaschinen widersprüchlich; zwei Angebote sind schlicht die Wahrheit.
     */
    $angebote = $leistungen->map(fn ($l) => [
        '@type' => 'Offer',
        'name' => $l->name,
        'description' => $l->description,
        'price' => $l->price,
        'priceCurrency' => 'EUR',
        'category' => $l->category->name,
    ])->all();

    $abos = $leistungen->filter->hatAbo()->map(fn ($l) => array_filter([
        '@type' => 'Offer',
        'name' => $l->name.' – monatlich',
        'description' => trim($l->description.' '.$l->aboBedingungen()),
        'category' => $l->category->name,
        'priceSpecification' => [
            '@type' => 'UnitPriceSpecification',
            'price' => $l->monthly_price,
            'priceCurrency' => 'EUR',
            'referenceQuantity' => [
                '@type' => 'QuantitativeValue',
                'value' => 1,
                'unitCode' => 'MON',
            ],
        ],
    ]))->values()->all();

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
            'itemListElement' => array_merge($angebote, $abos),
        ],
    ];
@endphp

<x-layouts.oeffentlich
    titel="Leistungen und Preise"
    beschreibung="Webentwicklung, KI-Automatisierung, Hosting und Pflege – monatlich ab 99 € inklusive Hosting und Pflege, oder einmalig zum Festpreis. Alle Preise auf einen Blick."
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

    {{-- .preise trägt den Umschalter: das :has() in app.css greift von hier
         aus auf die Karten weiter unten. --}}
    <div class="preise mx-auto max-w-6xl px-5 py-14">

        <fieldset class="mb-4 flex justify-center">
            <legend class="sr-only">Wie möchtest du zahlen?</legend>

            <div class="inline-flex rounded-xl border border-linie bg-karte p-1">
                <input type="radio" name="preismodell" id="preis-monatlich"
                       class="preiswahl__radio sr-only" checked>
                <label for="preis-monatlich" class="preiswahl__knopf">Monatlich</label>

                <input type="radio" name="preismodell" id="preis-einmalig"
                       class="preiswahl__radio sr-only">
                <label for="preis-einmalig" class="preiswahl__knopf">Einmalig</label>
            </div>
        </fieldset>

        <p class="mx-auto mb-12 max-w-2xl text-center text-sm leading-relaxed text-text-leise">
            <span data-preis="monatlich">
                Ein Preis, in dem alles steckt: Erstellung, Hosting, Pflege und Updates.
                Statt einer Rechnung über mehrere tausend Euro zahlst du 149 € zum Start
                und danach einen festen Monatsbetrag – wie Strom oder Telefon.
            </span>
            <span data-preis="einmalig">
                Du zahlst die Erstellung einmal und bist damit durch. Hosting und Pflege
                buchst du getrennt dazu – nötig sind sie trotzdem, denn betreiben muss
                die Seite jemand.
            </span>
        </p>

        @foreach ($gruppen as $gruppe)
            <section class="mb-14" aria-labelledby="gruppe-{{ $gruppe->slug }}">
                <h2 id="gruppe-{{ $gruppe->slug }}" class="text-2xl" data-auftritt="0">{{ $gruppe->name }}</h2>

                @if ($gruppe->note)
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-text-leise" data-auftritt="0">
                        {{ $gruppe->note }}
                    </p>
                @endif

                <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($gruppe->services as $i => $leistung)
                        <article class="flex flex-col rounded-2xl border border-linie bg-karte p-6 transition-colors hover:border-akzent/40"
                                 data-auftritt="{{ $i }}">
                            @if ($leistung->icon)
                                <x-symbol :name="$leistung->icon" klasse="mb-3 h-7 w-7 text-akzent" />
                            @endif

                            <h3 class="text-lg">{{ $leistung->name }}</h3>

                            <p class="mt-2 flex-1 text-sm leading-relaxed text-text-leise">
                                {{ $leistung->description }}
                            </p>

                            <div class="mt-5" data-preis="monatlich">
                                <p class="font-display text-xl text-akzent">{{ $leistung->aboAnsicht() }}</p>

                                @if ($leistung->aboBedingungen())
                                    <p class="mt-1 text-xs leading-relaxed text-text-leise">
                                        {{ $leistung->aboBedingungen() }}
                                    </p>
                                @endif

                                @if ($leistung->subscription_includes)
                                    <p class="mt-2 text-xs leading-relaxed text-text-leise">
                                        <span class="text-text">Enthalten:</span>
                                        {{ $leistung->subscription_includes }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-5" data-preis="einmalig">
                                <p class="font-display text-xl text-akzent">
                                    {{ $leistung->priceLabel() ?? 'auf Anfrage' }}
                                </p>

                                @if ($leistung->hatAbo())
                                    <p class="mt-1 text-xs leading-relaxed text-text-leise">
                                        Hosting und Pflege kommen getrennt dazu.
                                    </p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endforeach

        <section class="rounded-2xl border border-linie bg-karte p-8" data-auftritt="0">
            <h2 class="text-xl">Wie die zwei Wege sich unterscheiden</h2>

            <p class="mt-4 leading-relaxed text-text-leise">
                Es ist dieselbe Arbeit und dieselbe Website – die Frage ist nur, ob du die
                Erstellung auf einmal bezahlst oder über die Laufzeit verteilt. Im Abo
                sind Hosting, Pflege und ein fester Umfang an Änderungen enthalten; beim
                Festpreis buchst du beides getrennt dazu. Nach der Mindestlaufzeit läuft
                das Abo zum niedrigeren Pflegepreis weiter und ist monatlich kündbar.
            </p>

            <p class="mt-4 leading-relaxed text-text-leise">
                Was über den enthaltenen Umfang hinausgeht – neue Funktionen, zusätzliche
                Seiten, ein Redesign – rechnen wir in beiden Fällen nach Aufwand ab.
                Größere Projekte bekommen ein eigenes Angebot mit längerer Laufzeit;
                der veröffentlichte Katalog endet bei 429 € im Monat. Die
                Einrichtungsgebühr von 149 € fällt einmalig beim Start an und ist keine
                Anzahlung auf einen Kaufpreis – die Website bleibt während der Laufzeit
                unsere, gepflegt und betrieben für dich.
            </p>

            {{-- Inhaltlich unverändert von der alten Leistungsseite übernommen:
                 die Aussage zur Inhaberschaft der Zugänge ist eine Zusage an den
                 Kunden und darf sich beim Umbau nicht still verändern. --}}
            <p class="mt-4 leading-relaxed text-text-leise">
                Domain und Hosting werden <strong class="text-text">im Auftrag des Kunden</strong>
                bei einem Anbieter wie STRATO gebucht. Der Kunde bleibt jederzeit
                <strong class="text-text">rechtlicher Inhaber aller Zugänge</strong> –
                das gilt im Abo genauso wie beim Festpreis. Wir übernehmen die technische
                Einrichtung, Verwaltung und laufende Pflege, damit du dich um nichts
                kümmern musst.
            </p>

            <p class="mt-4 text-sm leading-relaxed text-text-leise">
                Alle Angebote richten sich an Unternehmen, Selbstständige und Freiberufler.
                Es gilt die Kleinunternehmerregelung nach § 19 UStG, die Preise enthalten
                daher keine Umsatzsteuer.
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
