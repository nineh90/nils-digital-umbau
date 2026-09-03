@php
    $bild = $post->product?->image ?? $post->hero_image;

    /*
     * BlogPosting mit author, publisher und datePublished.
     *
     * Die alte Seite erzeugte das JSON-LD erst per JavaScript im Browser –
     * für Crawler, die kein JS ausführen, war es schlicht nicht vorhanden.
     */
    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->title,
        'description' => $post->teaser,
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at?->toIso8601String(),
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('blog.show', $post)],
        'author' => ['@type' => 'Person', 'name' => 'Nils Nehring', 'url' => url('/')],
        'publisher' => ['@type' => 'Organization', 'name' => 'Nils-Digital', 'url' => url('/')],
        'inLanguage' => 'de-DE',
    ];

    if ($bild) {
        $jsonld['image'] = url(ltrim($bild, '/'));
    }

    if ($post->category) {
        $jsonld['articleSection'] = $post->category->name;
    }

    $strukturDaten = [$jsonld, [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Startseite', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('blog.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => route('blog.show', $post)],
        ],
    ]];

    if ($post->product) {
        $strukturDaten[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $post->product->name,
            'image' => $post->product->image ? url(ltrim($post->product->image, '/')) : null,
            'offers' => [
                '@type' => 'Offer',
                'price' => $post->product->price,
                'priceCurrency' => $post->product->currency,
                'availability' => 'https://schema.org/'.($post->product->availability ?? 'InStock'),
                'url' => $post->product->shop_url,
            ],
        ];
    }
@endphp

@php
    // Die Vorschau kommt aus der Redaktion und ist nur angemeldet erreichbar.
    // noindex trotzdem: ein weitergegebener Link soll nicht im Index landen.
    $vorschau ??= false;
@endphp

<x-layouts.oeffentlich
    :titel="$post->title"
    :beschreibung="$post->teaser"
    :bild="$bild"
    typ="article"
    :robots="$vorschau ? 'noindex, nofollow' : null"
    :jsonld="$strukturDaten">

    @if ($vorschau)
        <div class="border-b border-akzent/30 bg-flaeche-2">
            <div class="mx-auto flex max-w-3xl flex-wrap items-center gap-x-4 gap-y-2 px-5 py-3 text-sm">
                <span class="rounded-full bg-akzent px-2.5 py-1 text-xs font-medium text-flaeche">Vorschau</span>

                <span class="text-text-leise">
                    @if ($post->status !== 'published')
                        Entwurf – öffentlich noch nicht erreichbar.
                    @elseif (! $post->published_at?->isPast())
                        Geplant für {{ $post->published_at?->translatedFormat('d. F Y') }} – bis dahin nicht öffentlich.
                    @else
                        Dieser Beitrag ist bereits veröffentlicht.
                    @endif
                </span>

                <a href="{{ url('/admin/posts/'.$post->id.'/edit') }}"
                   class="ml-auto text-akzent underline underline-offset-4 hover:text-akzent-hell">
                    Zurück zum Bearbeiten
                </a>
            </div>
        </div>
    @endif

    <article class="mx-auto max-w-3xl px-5 py-14">

        <nav aria-label="Sie sind hier" class="mb-6 text-sm text-text-leise">
            <a href="{{ route('blog.index') }}" class="hover:text-akzent">Blog</a>
            @if ($post->category)
                <span aria-hidden="true"> / </span>
                <a href="{{ route('blog.kategorie', $post->category) }}" class="hover:text-akzent">
                    {{ $post->category->name }}
                </a>
            @endif
        </nav>

        <header class="mb-8">
            <h1 class="text-3xl leading-tight sm:text-4xl">{{ $post->title }}</h1>

            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-text-leise">
                <time datetime="{{ $post->published_at?->toDateString() }}">
                    {{ $post->published_at?->translatedFormat('d. F Y') }}
                </time>
                <span aria-hidden="true">·</span>
                <span>{{ $post->readingMinutes() }} Min. Lesezeit</span>
            </div>

            <p class="mt-6 border-l-2 border-akzent pl-5 text-lg leading-relaxed text-text-leise">
                {{ $post->teaser }}
            </p>
        </header>

        @if ($post->product)
            <aside class="mb-10 overflow-hidden rounded-2xl border border-linie bg-karte sm:flex">
                @if ($post->product->image)
                    <img src="/{{ ltrim($post->product->image, '/') }}"
                         alt="{{ $post->product->name }}"
                         class="h-56 w-full object-cover sm:h-auto sm:w-52 sm:shrink-0">
                @endif
                <div class="flex flex-col justify-center p-6">
                    <p class="font-display text-xl">{{ $post->product->name }}</p>
                    @if ($post->product->price)
                        <p class="mt-2 text-2xl text-akzent">
                            {{ number_format((float) $post->product->price, 2, ',', '.') }} €
                        </p>
                    @endif
                    @if ($post->product->shop_url)
                        <a href="{{ $post->product->shop_url }}"
                           rel="noopener"
                           class="mt-4 inline-block self-start rounded-lg bg-akzent px-5 py-2.5 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                            Im Shop ansehen
                        </a>
                    @endif
                </div>
            </aside>
        @elseif ($post->hero_image)
            <img src="/{{ ltrim($post->hero_image, '/') }}"
                 alt=""
                 class="mb-10 w-full rounded-2xl border border-linie {{ $post->thumb_fit === 'contain' ? 'bg-flaeche-2 object-contain p-8' : 'object-cover' }}">
        @endif

        <div class="fliesstext">
            {!! $post->contentHtml() !!}
        </div>

        @if (! $post->product && $post->links->isNotEmpty())
            <div class="mt-10 flex flex-wrap gap-3">
                @foreach ($post->links as $link)
                    <a href="{{ $link->url }}"
                       @if (! str_starts_with($link->url, '/')) rel="noopener" @endif
                       class="rounded-lg border border-akzent px-5 py-2.5 text-sm text-akzent transition-colors hover:bg-akzent hover:text-flaeche">
                        {{ $link->label }}
                    </a>
                @endforeach
            </div>
        @endif

        @if ($weitere->isNotEmpty())
            <section class="mt-16 border-t border-linie pt-10">
                <h2 class="mb-6 text-xl">Weiterlesen</h2>
                <ul class="space-y-4">
                    @foreach ($weitere as $andere)
                        <li>
                            <a href="{{ route('blog.show', $andere) }}"
                               class="group block rounded-xl border border-linie bg-karte p-4 transition-colors hover:border-akzent/40">
                                <span class="block text-sm text-text-leise">
                                    {{ $andere->category?->name }} ·
                                    {{ $andere->published_at?->translatedFormat('d.m.Y') }}
                                </span>
                                <span class="mt-1 block transition-colors group-hover:text-akzent">
                                    {{ $andere->title }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

    </article>

</x-layouts.oeffentlich>
