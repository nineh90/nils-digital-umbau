@props(['projekt'])

@php
    $einpassen = $projekt->image_fit !== 'cover';

    $stati = [
        'live' => ['Live', 'bg-emerald-500/15 text-emerald-300'],
        'beta' => ['Beta', 'bg-amber-500/15 text-amber-300'],
        'planned' => ['Geplant', 'bg-white/10 text-text-leise'],
    ];
    [$statusLabel, $statusKlasse] = $stati[$projekt->status] ?? [null, null];
@endphp

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-linie bg-karte transition-colors hover:border-akzent/40">

    <div class="aspect-[2/1] overflow-hidden bg-flaeche-2">
        @if ($projekt->image)
            <img src="/{{ ltrim($projekt->image, '/') }}"
                 alt=""
                 loading="lazy"
                 @class([
                     'h-full w-full transition-transform duration-500 group-hover:scale-105',
                     'object-contain p-5' => $einpassen,
                     'object-cover' => ! $einpassen,
                 ])>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">

        <div class="mb-2 flex flex-wrap items-center gap-2 text-xs">
            @if ($projekt->type)
                <span class="text-text-leise">{{ $projekt->type }}</span>
            @endif
            @if ($statusLabel)
                <span class="rounded-full px-2 py-0.5 {{ $statusKlasse }}">{{ $statusLabel }}</span>
            @endif
        </div>

        <h2 class="text-lg">
            <a href="{{ route('projekte.show', $projekt) }}" class="transition-colors hover:text-akzent">
                <span class="absolute inset-0"></span>
                {{ $projekt->title }}
            </a>
        </h2>

        <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-text-leise">
            {{ $projekt->description }}
        </p>

        @if ($projekt->tags)
            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach (array_slice($projekt->tags, 0, 4) as $tag)
                    <span class="rounded border border-linie px-2 py-0.5 text-xs text-text-leise">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

    </div>
</article>
