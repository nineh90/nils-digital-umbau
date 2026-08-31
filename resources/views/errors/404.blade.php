{{--
    404-Seite.

    Die alte Seite hatte gar keine – wer sich vertippte, landete auf der
    nackten Apache-Fehlermeldung. Nach einem Umzug mit geänderten Adressen ist
    das die Seite, die am häufigsten unerwartet aufgerufen wird; sie sollte
    weiterhelfen statt zu entschuldigen.
--}}

<x-layouts.oeffentlich titel="Seite nicht gefunden" robots="noindex, follow">

    <div class="mx-auto max-w-2xl px-5 py-24 text-center">
        <p class="font-display text-6xl text-akzent">404</p>

        <h1 class="mt-6 text-2xl sm:text-3xl">Diese Seite gibt es nicht</h1>

        <p class="mt-4 leading-relaxed text-text-leise">
            Vielleicht ein Tippfehler, vielleicht ein alter Link. Beides halb so wild –
            hier geht es weiter:
        </p>

        <div class="mt-10 grid gap-4 text-left sm:grid-cols-2">
            @foreach ([
                ['start', 'Startseite', 'Zurück zum Anfang'],
                ['leistungen', 'Leistungen', 'Was wir machen und was es kostet'],
                ['projekte', 'Projekte', 'Was wir gebaut haben'],
                ['blog.index', 'Blog', 'Beiträge aus der Praxis'],
            ] as [$route, $titel, $text])
                <a href="{{ route($route) }}"
                   class="rounded-xl border border-linie bg-karte p-5 transition-colors hover:border-akzent/50">
                    <span class="block">{{ $titel }}</span>
                    <span class="mt-1 block text-sm text-text-leise">{{ $text }}</span>
                </a>
            @endforeach
        </div>

        <p class="mt-10 text-sm text-text-leise">
            Etwas gesucht und nicht gefunden?
            <a href="{{ route('kontakt') }}" class="text-akzent hover:underline">Sag uns Bescheid.</a>
        </p>
    </div>

</x-layouts.oeffentlich>
