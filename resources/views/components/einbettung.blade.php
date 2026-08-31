@props([
    'quelle',
    'titel',
    'anbieter',
    'hinweis' => null,
    'hoehe' => '1200px',
    'direktlink' => null,
])

{{--
    Externe Einbettung mit Zustimmung.

    Der iframe wird bewusst nicht mitgeliefert, sondern erst per JavaScript
    eingesetzt, wenn jemand klickt. Vorher fließt nichts zum Anbieter – weder
    Cookies noch IP-Adresse.

    Ohne JavaScript bleibt diese Vorschau samt Direktlink sichtbar, die Seite
    ist also weiterhin benutzbar.
--}}

<div data-einbettung="{{ $quelle }}"
     data-einbettung-titel="{{ $titel }}"
     data-einbettung-hoehe="{{ $hoehe }}"
     class="rounded-2xl border border-linie bg-karte p-8 text-center">

    <p class="font-display text-lg">{{ $titel }}</p>

    <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-text-leise">
        {{ $hinweis ?? "Dieser Inhalt wird von {$anbieter} bereitgestellt. Beim Laden werden Daten an {$anbieter} übertragen, unter anderem deine IP-Adresse." }}
    </p>

    <button type="button" data-einbettung-laden
            class="mt-6 rounded-lg bg-akzent px-6 py-3 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
        Inhalt laden
    </button>

    <p class="mt-4 text-xs text-text-leise">
        Oder direkt
        <a href="{{ $direktlink ?? $quelle }}" target="_blank" rel="noopener"
           class="text-akzent hover:underline">bei {{ $anbieter }} öffnen ↗</a>
        ·
        <a href="{{ route('datenschutz') }}" class="text-akzent hover:underline">Datenschutz</a>
    </p>
</div>
