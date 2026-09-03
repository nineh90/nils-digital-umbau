@php
    /*
     * Die Personen kommen aus der Redaktion, nicht mehr aus einem Array hier
     * oben. Der Schema.org-Block baut auf derselben Sammlung auf – so kann
     * eine neue Person nicht in der Auszeichnung fehlen, weil sie jemand nur
     * an einer von zwei Stellen eingetragen hat.
     */
    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Nils-Digital',
        'url' => url('/'),
        'employee' => $team->map(fn ($person) => [
            '@type' => 'Person',
            'name' => $person->name,
            'jobTitle' => $person->role,
        ])->all(),
    ];
@endphp

<x-layouts.oeffentlich
    titel="Das Team"
    beschreibung="Nils und Kevin – zwei Entwickler mit Fokus auf moderne Webseiten, KI-Automatisierung und individuelle digitale Lösungen."
    :jsonld="$jsonld">

    <x-seitenkopf
        ueberschrift="Das Team"
        text="Wir sind Nils und Kevin – zwei Entwickler mit Fokus auf moderne Webseiten, KI-Automatisierung und individuelle digitale Lösungen. Kein anonymes Unternehmen, keine Zwischenhändler." />

    <div class="mx-auto max-w-4xl px-5 py-14">

        <div class="space-y-8">
            @foreach ($team as $i => $person)
                <article class="overflow-hidden rounded-2xl border border-linie bg-karte sm:flex"
                         data-auftritt="{{ $i }}">
                    <div class="shrink-0 sm:w-52">
                        @if ($person->image)
                            <img src="/{{ ltrim($person->image, '/') }}" alt="{{ $person->name }}"
                                 class="h-56 w-full object-cover sm:h-full">
                        @else
                            {{-- Ohne Foto ein Monogramm statt einer leeren Fläche. --}}
                            <div class="flex h-56 w-full items-center justify-center bg-flaeche-2 font-display text-5xl text-akzent sm:h-full"
                                 aria-hidden="true">
                                {{ $person->monogramm() }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 p-6">
                        <h2 class="text-xl">{{ $person->name }}</h2>
                        <p class="text-sm text-akzent">{{ $person->role }}</p>

                        <p class="mt-4 leading-relaxed text-text-leise">{{ $person->bio }}</p>

                        @if ($person->skills)
                            <div class="mt-5 flex flex-wrap gap-1.5">
                                @foreach ($person->skills as $schlagwort)
                                    <span class="rounded border border-linie px-2 py-0.5 text-xs text-text-leise">{{ $schlagwort }}</span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Beides optional: eine neue Person darf ohne den
                             hervorgehobenen Satz auskommen, ohne dass ein
                             Gedankenstrich ins Leere zeigt. --}}
                        @if ($person->highlight_text)
                            <p class="mt-5 border-l-2 border-akzent pl-4 text-sm text-text-leise">
                                @if ($person->highlight_label)
                                    <strong class="text-text">{{ $person->highlight_label }}</strong> —
                                @endif
                                {{ $person->highlight_text }}
                            </p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <section class="mt-14 rounded-2xl border border-akzent/30 bg-flaeche-2 p-8 text-center"
                 data-auftritt="0" data-aus="naeher">
            <h2 class="text-xl">Projekte jeder Größe</h2>
            <p class="mx-auto mt-3 max-w-xl text-text-leise">
                Von der digitalen Visitenkarte bis zur komplexen Webanwendung.
                Sprich uns einfach an.
            </p>
            <a href="{{ route('kontakt') }}"
               class="mt-6 inline-block rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                Projekt besprechen
            </a>
        </section>

    </div>

</x-layouts.oeffentlich>
