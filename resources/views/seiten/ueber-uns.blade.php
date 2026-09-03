@php
    $werte = [
        ['Persönlich', 'Du arbeitest direkt mit uns – vom ersten Gespräch bis nach dem Launch dieselben Ansprechpartner. Kein anonymes Support-Team, keine wechselnden Zuständigkeiten.'],
        ['Individuell', 'Kein Copy-Paste aus dem Template-Baukasten. Jede Lösung wird auf deine Anforderungen zugeschnitten.'],
        ['Transparent', 'Klare Preise, ehrliche Kommunikation. Du weißt jederzeit, woran wir arbeiten und was dich das kostet.'],
        ['Langfristig', 'Wir denken über das Projekt hinaus. Technische Qualität und Wartbarkeit sind für uns kein Bonus – sie sind Standard.'],
    ];

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'AboutPage',
        'url' => route('ueber-uns'),
        'mainEntity' => [
            '@type' => 'Organization',
            'name' => 'Nils-Digital',
            'url' => url('/'),
            'email' => 'info@nils-digital.de',
            'founder' => ['@type' => 'Person', 'name' => 'Nils Nehring'],
        ],
    ];
@endphp

<x-layouts.oeffentlich
    titel="Über uns"
    beschreibung="Nils-Digital steht für persönliche Betreuung, individuelle Lösungen und direkte Kommunikation – kein anonymes Unternehmen, keine Zwischenhändler."
    :jsonld="$jsonld">

    <x-seitenkopf
        ueberschrift="Über uns"
        text="Nils-Digital steht für persönliche Betreuung, individuelle Lösungen und direkte Kommunikation – kein anonymes Unternehmen, keine Zwischenhändler." />

    <div class="mx-auto max-w-4xl px-5 py-14">

        <section class="mb-16" data-auftritt="0">
            <h2 class="text-2xl">Was uns antreibt</h2>
            <div class="fliesstext mt-5 text-text-leise">
                <p>
                    Wir glauben daran, dass digitale Lösungen wirklich für Menschen arbeiten sollten –
                    nicht umgekehrt. Ob eine moderne Website, ein automatisierter Workflow oder eine
                    individuelle App: Unser Ziel ist immer, dass du als Kunde einen echten Mehrwert
                    bekommst und nicht einfach ein weiteres Produkt von der Stange.
                </p>
                <p>
                    Genau deshalb nehmen wir uns die Zeit, dein Projekt, deine Ziele und deine
                    Arbeitsweise wirklich zu verstehen – bevor wir anfangen zu entwickeln.
                </p>
            </div>
        </section>

        <section class="mb-16">
            <h2 class="mb-6 text-2xl" data-auftritt="0">Wofür wir stehen</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                @foreach ($werte as $i => [$titel, $text])
                    <article class="rounded-2xl border border-linie bg-karte p-6" data-auftritt="{{ $i }}">
                        <h3 class="text-lg text-akzent">{{ $titel }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-text-leise">{{ $text }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="mb-16" data-auftritt="0">
            <h2 class="text-2xl">Wie wir arbeiten</h2>
            <div class="fliesstext mt-5 text-text-leise">
                <p>
                    Ein Projekt bei Nils-Digital beginnt immer mit einem Gespräch – kein Formular,
                    kein Briefing-Template. Wir wollen verstehen, was du brauchst und was dich bewegt.
                    Dann entwickeln wir gemeinsam eine Lösung, die wirklich passt.
                </p>
                <p>
                    Während der Umsetzung bleiben wir in engem Austausch: Du siehst den Fortschritt,
                    kannst Feedback geben und weißt immer, wo dein Projekt gerade steht.
                </p>
                <p>
                    Nach dem Launch lassen wir dich nicht allein – wir sind weiterhin da, wenn etwas
                    geändert oder erweitert werden soll.
                </p>
            </div>
        </section>

        <section class="rounded-2xl border border-akzent/30 bg-flaeche-2 p-8 text-center"
                 data-auftritt="0" data-aus="naeher">
            <h2 class="text-xl">Neugierig, wer hinter Nils-Digital steckt?</h2>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('team') }}"
                   class="rounded-lg bg-akzent px-5 py-2.5 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                    Das Team kennenlernen
                </a>
                <a href="{{ route('kontakt') }}"
                   class="rounded-lg border border-linie px-5 py-2.5 transition-colors hover:border-akzent hover:text-akzent">
                    Projekt besprechen
                </a>
            </div>
        </section>

    </div>

</x-layouts.oeffentlich>
