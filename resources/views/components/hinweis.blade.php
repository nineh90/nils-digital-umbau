@props(['stelle' => 'unten'])

@php
    /*
     * Zweimal im Layout eingebunden, einmal je Stelle: die Leiste oben gehoert
     * in den Textfluss vor die Kopfzeile, sonst legt sie sich ueber die
     * Navigation. Fenster und Ecke liegen ohnehin ueber der Seite und stehen
     * deshalb am Ende, damit sie im Quelltext den Inhalt nicht vor sich
     * herschieben.
     *
     * aktueller() faellt beim zweiten Aufruf in denselben Request-Speicher,
     * die Datenbank wird also nur einmal gefragt.
     */
    $hinweis = \App\Models\Notice::aktueller();

    $gehoertHierher = $hinweis && match ($stelle) {
        'oben' => $hinweis->placement === 'top',
        default => $hinweis->placement !== 'top',
    };
@endphp

@if ($gehoertHierher)
    {{--
        Aufbau bewusst in dieser Reihenfolge:

        Der Schließen-Knopf ist ein <label> zu einer versteckten Checkbox. Damit
        lässt sich der Hinweis ohne eine Zeile JavaScript wegklicken – :has()
        blendet ihn aus. Das Merken über den Besuch hinaus übernimmt danach das
        Skript; fällt es aus, erscheint der Hinweis beim nächsten Aufruf eben
        noch einmal. Lästig, aber nichts ist kaputt.

        Kein <dialog>: dessen Vorzüge (Fokusfalle, Escape) gibt es nur über
        showModal() und damit nur mit JavaScript. Ein <dialog open> hat sie
        nicht und kostet dafür die Bedienbarkeit ohne Skript.
    --}}
    {{-- In der Vorschau ohne hidden und ohne Gedaechtnis: sonst saehe man den
         Hinweis beim zweiten Nachschauen nicht mehr, weil man ihn beim ersten
         weggeklickt hat. --}}
    <div class="hinweis hinweis--{{ $hinweis->scheme }} hinweis--{{ $hinweis->placement }}"
         data-hinweis="{{ $hinweis->speicherSchluessel() }}"
         data-haeufigkeit="{{ \App\Models\Notice::istVorschau() ? 'always' : $hinweis->frequency }}"
         @unless (\App\Models\Notice::istVorschau()) hidden @endunless>

        <input type="checkbox" id="hinweis-zu" class="hinweis__schalter sr-only">

        @if ($hinweis->placement === 'center')
            <label for="hinweis-zu" class="hinweis__schleier" aria-hidden="true"></label>
        @endif

        <div class="hinweis__tafel" role="dialog" aria-modal="false"
             aria-labelledby="hinweis-titel">

            @if ($hinweis->image)
                <img src="/{{ ltrim($hinweis->image, '/') }}" alt=""
                     class="hinweis__bild" width="600" height="300">
            @endif

            <div class="hinweis__text">
                @if ($hinweis->icon)
                    <x-symbol :name="$hinweis->icon" klasse="hinweis__symbol" />
                @endif

                <p id="hinweis-titel" class="hinweis__titel">{{ $hinweis->title }}</p>
                <p class="hinweis__rumpf">{{ $hinweis->body }}</p>

                @if ($hinweis->button_label && $hinweis->button_url)
                    <a href="{{ $hinweis->button_url }}" class="hinweis__knopf">
                        {{ $hinweis->button_label }}
                    </a>
                @endif
            </div>

            <label for="hinweis-zu" class="hinweis__schliessen" tabindex="0" role="button">
                <span class="sr-only">Hinweis schließen</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" aria-hidden="true">
                    <path d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </label>
        </div>
    </div>
@endif
