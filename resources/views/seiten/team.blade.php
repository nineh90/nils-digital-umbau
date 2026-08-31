@php
    $team = [
        [
            'name' => 'Nils Nehring',
            'rolle' => 'Gründer & Lead-Entwickler',
            'bild' => 'assets/images/sunny-nils.jpg',
            'text' => 'Nils ist Gründer von Nils-Digital und dein direkter Ansprechpartner für Konzept, Umsetzung und Kommunikation. Er entwickelt Webseiten, Apps und digitale Lösungen, die nicht nur gut aussehen, sondern echte Ergebnisse liefern – von der ersten Idee bis zum Launch.',
            'faehigkeiten' => ['Webdesign', 'Frontend', 'Backend', 'KI-Automatisierung', 'SEO', 'Projektleitung'],
            'merkmal' => ['Arbeitsweise', 'Direkte Kommunikation, kurze Wege, transparente Absprachen. Kein Ticket-System, kein anonymes Support-Team.'],
        ],
        [
            'name' => 'Kevin',
            'rolle' => 'Entwickler',
            'bild' => null,
            'text' => 'Kevin sorgt dafür, dass alles technisch sauber, stabil und zuverlässig läuft – besonders wenn Projekte komplex werden. Mit seinem Blick fürs Detail und strukturiertem Code liefert er genau die technische Tiefe, die anspruchsvolle Projekte brauchen.',
            'faehigkeiten' => ['JavaScript', 'Frontend', 'Backend', 'Clean Code', 'Problemlösung'],
            'merkmal' => ['Stärke', 'Kevin hat in kürzester Zeit über 70 Tickets umgesetzt – präzise, strukturiert und schneller als erwartet.'],
        ],
    ];

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Nils-Digital',
        'url' => url('/'),
        'employee' => collect($team)->map(fn ($m) => [
            '@type' => 'Person',
            'name' => $m['name'],
            'jobTitle' => $m['rolle'],
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
            @foreach ($team as $person)
                <article class="overflow-hidden rounded-2xl border border-linie bg-karte sm:flex">
                    <div class="shrink-0 sm:w-52">
                        @if ($person['bild'])
                            <img src="/{{ $person['bild'] }}" alt="{{ $person['name'] }}"
                                 class="h-56 w-full object-cover sm:h-full">
                        @else
                            {{-- Ohne Foto ein Monogramm statt einer leeren Fläche. --}}
                            <div class="flex h-56 w-full items-center justify-center bg-flaeche-2 font-display text-5xl text-akzent sm:h-full"
                                 aria-hidden="true">
                                {{ mb_substr($person['name'], 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 p-6">
                        <h2 class="text-xl">{{ $person['name'] }}</h2>
                        <p class="text-sm text-akzent">{{ $person['rolle'] }}</p>

                        <p class="mt-4 leading-relaxed text-text-leise">{{ $person['text'] }}</p>

                        <div class="mt-5 flex flex-wrap gap-1.5">
                            @foreach ($person['faehigkeiten'] as $f)
                                <span class="rounded border border-linie px-2 py-0.5 text-xs text-text-leise">{{ $f }}</span>
                            @endforeach
                        </div>

                        <p class="mt-5 border-l-2 border-akzent pl-4 text-sm text-text-leise">
                            <strong class="text-text">{{ $person['merkmal'][0] }}</strong> —
                            {{ $person['merkmal'][1] }}
                        </p>
                    </div>
                </article>
            @endforeach
        </div>

        <section class="mt-14 rounded-2xl border border-akzent/30 bg-flaeche-2 p-8 text-center">
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
