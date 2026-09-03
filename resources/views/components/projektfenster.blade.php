@props([
    'projekt',
    'neigung' => 0,
    'sofort' => false,
])

{{--
    Stilisiertes Browser-Fenster um einen Projekt-Screenshot.

    Der Rahmen ist kein Zierrat: ein Screenshot ohne ihn schwebt als
    zusammenhangloses Rechteck im Raum, mit ihm liest man sofort "das ist eine
    fertige, laufende Website". Deshalb auch die Adresszeile mit der echten
    Adresse – sie ist der eigentliche Beweis.

    Ohne verlinkte Adresse steht dort der Projekttyp. Ein leeres Feld sähe nach
    Attrappe aus, und genau das soll es ja nicht sein.
--}}

@php
    $adresse = $projekt->link
        ? preg_replace('#^https?://(www\.)?#', '', rtrim($projekt->link, '/'))
        : ($projekt->type ?: 'nils-digital.de');
@endphp

<figure class="overflow-hidden rounded-xl border border-linie bg-karte shadow-2xl shadow-black/50"
        style="rotate: {{ $neigung }}deg">

    {{-- Fensterleiste --}}
    <div class="flex items-center gap-2 border-b border-linie bg-flaeche-2 px-3 py-2">
        <span aria-hidden="true" class="flex gap-1.5">
            <span class="h-2.5 w-2.5 rounded-full bg-white/15"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-white/15"></span>
            <span class="h-2.5 w-2.5 rounded-full bg-white/15"></span>
        </span>
        <span class="truncate rounded bg-flaeche/70 px-2 py-0.5 font-mono text-[0.65rem] text-text-leise">
            {{ $adresse }}
        </span>
    </div>

    {{-- Über der Falz muss das Bild sofort laden. loading="lazy" verzögert
         hier genau das größte Element im ersten Blickfeld und verschlechtert
         damit ausgerechnet den Wert, den Google als Ladegefühl misst. --}}
    <img src="/{{ ltrim($projekt->image, '/') }}"
         alt="Screenshot: {{ $projekt->title }}"
         loading="{{ $sofort ? 'eager' : 'lazy' }}"
         @if ($sofort) fetchpriority="high" @endif
         decoding="async"
         class="block aspect-[16/10] w-full object-cover object-top">

    <figcaption class="sr-only">{{ $projekt->title }}</figcaption>
</figure>
