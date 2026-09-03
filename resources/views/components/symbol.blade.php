@props([
    'name',
    'klasse' => 'h-6 w-6',
])

{{--
    Strichzeichnungen statt Emojis.

    Emojis werden von jedem Betriebssystem anders gezeichnet – auf Windows
    anders als auf dem iPhone, auf Android noch einmal anders. Damit ist das
    einzige durchgehend farbige Element der Seite ausgerechnet das, über das
    wir keine Kontrolle haben. Diese Symbole erben stattdessen die Textfarbe
    und passen sich damit jedem Zusammenhang an.

    Keine Icon-Bibliothek: gut zwei Dutzend Pfade rechtfertigen keine weitere
    Abhängigkeit im Bau. Die Pfade selbst stehen in App\Support\Symbole –
    dort, weil auch die Redaktion die Namen zur Auswahl braucht und zwei
    Listen, die dasselbe meinen, zuverlässig auseinanderlaufen.

    Bewusst ohne Titel und mit aria-hidden – die Symbole stehen überall neben
    einer Überschrift, die dasselbe schon in Worten sagt. Ein zweites Mal
    vorgelesen zu werden wäre nur Lärm.
--}}

@php
    use App\Support\Symbole;

    $pfad = Symbole::pfad($name);
@endphp

<svg {{ $attributes->merge(['class' => $klasse]) }}
     viewBox="0 0 24 24"
     fill="none"
     stroke="currentColor"
     stroke-width="1.5"
     stroke-linecap="round"
     stroke-linejoin="round"
     aria-hidden="true"
     focusable="false">
    {!! $pfad !!}
</svg>
