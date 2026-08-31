@php
    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Referenzen und Projekte',
        'url' => route('projekte'),
        'inLanguage' => 'de-DE',
        'about' => $projekte->map(fn ($p) => [
            '@type' => 'CreativeWork',
            'name' => $p->title,
            'url' => route('projekte.show', $p),
        ])->all(),
    ];
@endphp

<x-layouts.oeffentlich
    titel="Referenzen und Projekte"
    beschreibung="Websites, Apps und Automatisierungen, die wir gebaut haben – vom barrierefreien Auftritt einer Fahrlehrerin bis zur Pflegesoftware ohne Cloud."
    :jsonld="$jsonld">

    <div class="mx-auto max-w-6xl px-5 py-14">

        <header class="mb-12 max-w-2xl">
            <h1 class="text-3xl sm:text-4xl">Projekte</h1>
            <p class="mt-3 text-text-leise">
                Was wir gebaut haben – und warum es so gebaut ist.
            </p>
        </header>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($projekte as $projekt)
                <x-projektkachel :projekt="$projekt" />
            @endforeach
        </div>

    </div>

</x-layouts.oeffentlich>
