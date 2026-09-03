{{--
    Kopfzeile.

    Die Navigation steht als Liste im Markup, nicht als data-path-Attribut wie
    früher. Der aktive Punkt wird über den Routennamen erkannt.

    Die Struktur entspricht der alten Seite: sieben Punkte, davon Kontakt mit
    Untermenü. Sowohl das Untermenü als auch das Menü auf schmalen Geräten
    laufen über <details> – sie klappen ohne eine Zeile JavaScript auf, sind von
    Haus aus per Tastatur bedienbar und werden von Screenreadern korrekt als
    auf- und zuklappbar angesagt.
--}}

@php
    $punkte = [
        ['route' => 'start',      'label' => 'Startseite'],
        ['route' => 'leistungen', 'label' => 'Leistungen'],
        ['route' => 'projekte',   'label' => 'Projekte'],
        ['route' => 'team',       'label' => 'Das Team'],
        ['route' => 'ueber-uns',  'label' => 'Über uns'],
        ['route' => 'blog.index', 'label' => 'Blog'],
    ];

    $kontaktpunkte = [
        ['route' => 'kontakt',        'label' => 'Kontaktformular'],
        ['route' => 'projektanfrage', 'label' => 'Projektanfrage'],
        ['route' => 'termine',        'label' => 'Termine'],
    ];

    $aktiv = fn (string $route) => request()->routeIs($route)
        || ($route === 'blog.index' && request()->routeIs('blog.*'))
        || ($route === 'projekte' && request()->routeIs('projekte.*'));

    $kontaktAktiv = collect($kontaktpunkte)->contains(fn ($p) => request()->routeIs($p['route']));
@endphp

{{-- Die Navigation läuft bewusst über die Monospace: kurze Wörter, feste
     Breite, technischer Anklang – hier wirkt sie, ohne den Lesefluss zu
     stören wie im Fließtext. --}}
<header class="sticky top-0 z-40 border-b border-linie bg-flaeche/80 font-mono backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-3">

        <a href="{{ route('start') }}" class="flex shrink-0 items-center gap-2 font-display text-lg">
            <img src="/assets/images/logo/logo.png" alt="" width="32" height="32" class="h-8 w-8">
            <span>Nils-<span class="text-akzent">Digital</span></span>
        </a>

        <nav aria-label="Hauptmenü" class="hidden lg:block">
            <ul class="flex items-center gap-0.5">
                @foreach ($punkte as $punkt)
                    <li>
                        <a href="{{ route($punkt['route']) }}"
                           @class([
                               'rounded-lg px-3 py-2 text-sm transition-colors hover:text-akzent',
                               'text-akzent' => $aktiv($punkt['route']),
                               'text-text-leise' => ! $aktiv($punkt['route']),
                           ])
                           @if ($aktiv($punkt['route'])) aria-current="page" @endif>
                            {{ $punkt['label'] }}
                        </a>
                    </li>
                @endforeach

                <li>
                    <details class="group relative" name="kopfmenue">
                        <summary @class([
                                'flex cursor-pointer list-none items-center gap-1 rounded-lg px-3 py-2 text-sm transition-colors hover:text-akzent',
                                'text-akzent' => $kontaktAktiv,
                                'text-text-leise' => ! $kontaktAktiv,
                            ])>
                            Kontakt
                            <span aria-hidden="true" class="text-[0.6rem] transition-transform group-open:rotate-180">▼</span>
                        </summary>
                        <ul class="absolute right-0 mt-2 w-52 rounded-xl border border-linie bg-karte p-2 shadow-xl">
                            @foreach ($kontaktpunkte as $punkt)
                                <li>
                                    <a href="{{ route($punkt['route']) }}"
                                       @class([
                                           'block rounded-lg px-3 py-2 text-sm transition-colors hover:text-akzent',
                                           'text-akzent' => request()->routeIs($punkt['route']),
                                       ])>
                                        {{ $punkt['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                </li>

                <li class="ml-2">
                    <a href="{{ route('kundenbereich') }}"
                       class="rounded-lg border border-linie px-3 py-2 text-sm text-text-leise transition-colors hover:border-akzent hover:text-akzent">
                        Kundenbereich
                    </a>
                </li>
            </ul>
        </nav>

        <details class="relative lg:hidden" name="kopfmenue">
            <summary class="cursor-pointer list-none rounded-lg border border-linie px-3 py-2 text-sm">
                <span class="sr-only">Menü öffnen</span>
                <span aria-hidden="true">☰</span>
            </summary>
            <nav aria-label="Hauptmenü"
                 class="absolute right-0 mt-2 w-60 rounded-xl border border-linie bg-karte p-2 shadow-xl">
                <ul>
                    @foreach ($punkte as $punkt)
                        <li>
                            <a href="{{ route($punkt['route']) }}"
                               @class([
                                   'block rounded-lg px-3 py-2 text-sm',
                                   'text-akzent' => $aktiv($punkt['route']),
                               ])
                               @if ($aktiv($punkt['route'])) aria-current="page" @endif>
                                {{ $punkt['label'] }}
                            </a>
                        </li>
                    @endforeach

                    <li class="mt-1 border-t border-linie pt-1">
                        <p class="px-3 pt-1 pb-1 text-xs text-text-leise">Kontakt</p>
                        <ul>
                            @foreach ($kontaktpunkte as $punkt)
                                <li>
                                    <a href="{{ route($punkt['route']) }}"
                                       @class([
                                           'block rounded-lg px-3 py-2 text-sm',
                                           'text-akzent' => request()->routeIs($punkt['route']),
                                       ])>
                                        {{ $punkt['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li class="mt-1 border-t border-linie pt-1">
                        <a href="{{ route('kundenbereich') }}" class="block rounded-lg px-3 py-2 text-sm text-akzent">
                            Kundenbereich
                        </a>
                    </li>
                </ul>
            </nav>
        </details>

    </div>
</header>
