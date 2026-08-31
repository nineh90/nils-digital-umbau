@props(['beitrag'])

{{--
    Kachel in der Blog-Übersicht.

    thumbFit steuert wie bisher nur den Bildzuschnitt: "contain" passt das Bild
    vollständig ein – richtig für Logos –, sonst füllt es die Kachel und wird
    beschnitten, was für Fotos und Screenshots richtig ist.
--}}

@php
    $bild = $beitrag->product?->image ?? $beitrag->hero_image;
    $einpassen = $beitrag->thumb_fit === 'contain';
@endphp

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-linie bg-karte transition-colors hover:border-akzent/40">

    <a href="{{ route('blog.show', $beitrag) }}"
       class="block aspect-[2/1] overflow-hidden bg-flaeche-2"
       tabindex="-1"
       aria-hidden="true">
        @if ($bild)
            <img src="/{{ ltrim($bild, '/') }}"
                 alt=""
                 loading="lazy"
                 width="400" height="200"
                 @class([
                     'h-full w-full transition-transform duration-500 group-hover:scale-105',
                     'object-contain p-5' => $einpassen,
                     'object-cover' => ! $einpassen,
                 ])>
        @else
            <span class="flex h-full w-full items-center justify-center text-3xl opacity-25">✍️</span>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-5">

        <div class="mb-3 flex flex-wrap items-center gap-2 text-xs">
            @if ($beitrag->category)
                <a href="{{ route('blog.kategorie', $beitrag->category) }}"
                   class="rounded-full px-2.5 py-1 font-medium transition-opacity hover:opacity-80"
                   style="background: {{ $beitrag->category->color ?? 'rgba(255,255,255,.12)' }};
                          color: {{ $beitrag->category->text_color ?? '#fff' }};">
                    {{ $beitrag->category->name }}
                </a>
            @endif
            <time datetime="{{ $beitrag->published_at?->toDateString() }}" class="text-text-leise">
                {{ $beitrag->published_at?->translatedFormat('d. F Y') }}
            </time>
            <span class="text-text-leise">· {{ $beitrag->readingMinutes() }} Min.</span>
        </div>

        <h2 class="text-lg leading-snug">
            <a href="{{ route('blog.show', $beitrag) }}"
               class="transition-colors hover:text-akzent focus-visible:text-akzent">
                {{-- Streckt die Klickfläche über die ganze Kachel, ohne dass
                     Bild und Titel doppelt im Tab-Verlauf landen. --}}
                <span class="absolute inset-0"></span>
                {{ $beitrag->title }}
            </a>
        </h2>

        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-text-leise">
            {{ $beitrag->teaser }}
        </p>

    </div>
</article>
