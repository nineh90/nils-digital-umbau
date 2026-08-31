@props([
    'ueberschrift',
    'text' => null,
    'hintergrund' => null,
])

{{--
    Kopfzone der Unterseiten.

    Bewusst die ruhigere, flachere Schwester des Startseiten-Heros: mit
    Hintergrundbild, aber niedriger und ohne Bewegung. Sonst konkurriert jede
    Unterseite mit der Startseite und der Effekt nutzt sich beim dritten Klick ab.

    Ohne Hintergrundbild bleibt ein Verlauf – die Seite sieht auch dann fertig
    aus, wenn für ein Thema kein Motiv vorliegt.
--}}

<section class="relative overflow-hidden border-b border-linie">

    @if ($hintergrund)
        <img src="/{{ ltrim($hintergrund, '/') }}"
             alt=""
             aria-hidden="true"
             class="absolute inset-0 h-full w-full object-cover opacity-20">
    @endif

    {{-- Verlauf über dem Bild: hält den Text lesbar, egal wie hell das Motiv
         an der Stelle gerade ist. --}}
    <div aria-hidden="true"
         class="absolute inset-0 bg-gradient-to-b from-flaeche-2/70 via-flaeche/85 to-flaeche"></div>

    <div class="relative mx-auto max-w-6xl px-5 py-16 sm:py-20">
        <h1 class="max-w-3xl text-3xl leading-tight sm:text-4xl">{{ $ueberschrift }}</h1>

        @if ($text)
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-text-leise">{{ $text }}</p>
        @endif

        @if (isset($aktionen))
            <div class="mt-8 flex flex-wrap gap-3">{{ $aktionen }}</div>
        @endif
    </div>

</section>
