@php
    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Referenzen und Projekte',
        'url' => route('projekte'),
        'inLanguage' => 'de-DE',
        'about' => $projekte->concat($fruehere)->map(fn ($p) => [
            '@type' => 'CreativeWork',
            'name' => $p->title,
            'url' => route('projekte.show', $p),
        ])->all(),
    ];
@endphp

<x-layouts.oeffentlich
    titel="Referenzen & Projekte – Webentwicklung"
    beschreibung="Websites, Apps und Automatisierungen, die wir gebaut haben – vom barrierefreien Auftritt einer Fahrlehrerin bis zur Pflegesoftware ohne Cloud."
    :jsonld="$jsonld">

    <x-seitenkopf
        ueberschrift="Projekte"
        text="Was wir gebaut haben – und warum es so gebaut ist." />

    <div class="mx-auto max-w-6xl px-5 py-14">

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($projekte as $i => $projekt)
                <div data-auftritt="{{ $i }}">
                    <x-projektkachel :projekt="$projekt" />
                </div>
            @endforeach
        </div>

        {{-- Bewusst ohne Begründung: warum ein Projekt nicht mehr läuft, geht
             die Besucher nichts an. Gezeigt wird, was wir gebaut haben. --}}
        @if ($fruehere->isNotEmpty())
            <section class="mt-20 border-t border-linie pt-12" aria-labelledby="fruehere-projekte">
                <h2 id="fruehere-projekte" class="text-2xl">Frühere Projekte</h2>
                <p class="mt-2 max-w-2xl text-text-leise">
                    Seiten, die wir gebaut haben und die heute nicht mehr von uns betreut werden.
                </p>

                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($fruehere as $projekt)
                        <x-projektkachel :projekt="$projekt" />
                    @endforeach
                </div>
            </section>
        @endif

    </div>

</x-layouts.oeffentlich>
