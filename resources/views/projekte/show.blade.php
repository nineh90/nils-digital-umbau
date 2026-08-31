@php
    /*
     * Ohne Fallstudie ist die Seite dünn – Beschreibung, Schlagworte und ein
     * Link nach draußen. Solche Seiten schaden in der Suche mehr als sie nutzen,
     * deshalb erst indexieren, wenn im Feld "Fallstudie" wirklich etwas steht.
     * Erreichbar und verlinkt ist sie trotzdem, nur eben nicht für Google.
     */
    $robots = $project->hasCaseStudy() ? null : 'noindex, follow';

    $jsonld = [[
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'name' => $project->title,
        'description' => $project->description,
        'url' => route('projekte.show', $project),
        'inLanguage' => 'de-DE',
        'creator' => ['@type' => 'Organization', 'name' => 'Nils-Digital', 'url' => url('/')],
    ], [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Startseite', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Projekte', 'item' => route('projekte')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $project->title, 'item' => route('projekte.show', $project)],
        ],
    ]];

    $stati = ['live' => 'Live', 'beta' => 'Beta', 'planned' => 'Geplant'];
@endphp

<x-layouts.oeffentlich
    :titel="$project->title"
    :beschreibung="$project->description"
    :bild="$project->image"
    :robots="$robots"
    :jsonld="$jsonld">

    <div class="mx-auto max-w-3xl px-5 py-14">

        <nav aria-label="Sie sind hier" class="mb-6 text-sm text-text-leise">
            <a href="{{ route('projekte') }}" class="hover:text-akzent">Projekte</a>
        </nav>

        <header class="mb-10">
            <div class="mb-3 flex flex-wrap items-center gap-3 text-sm text-text-leise">
                @if ($project->type)
                    <span>{{ $project->type }}</span>
                @endif
                @if ($label = $stati[$project->status] ?? null)
                    <span aria-hidden="true">·</span>
                    <span>{{ $label }}</span>
                @endif
            </div>

            <h1 class="text-3xl leading-tight sm:text-4xl">{{ $project->title }}</h1>

            <p class="mt-5 text-lg leading-relaxed text-text-leise">{{ $project->description }}</p>

            @if ($project->link)
                <a href="{{ $project->link }}"
                   @if (! $project->is_internal) rel="noopener" target="_blank" @endif
                   class="mt-6 inline-block rounded-lg bg-akzent px-5 py-2.5 font-medium text-flaeche transition-colors hover:bg-akzent-hell">
                    Projekt ansehen
                    @unless ($project->is_internal)
                        <span aria-hidden="true">↗</span>
                        <span class="sr-only">(öffnet in neuem Tab)</span>
                    @endunless
                </a>
            @endif
        </header>

        @if ($project->image)
            <img src="/{{ ltrim($project->image, '/') }}"
                 alt=""
                 class="mb-10 w-full rounded-2xl border border-linie {{ $project->image_fit === 'cover' ? 'object-cover' : 'bg-flaeche-2 object-contain p-8' }}">
        @endif

        @if ($project->hasCaseStudy())
            <div class="fliesstext">
                {!! $project->bodyHtml() !!}
            </div>
        @endif

        @if ($project->tags)
            <div class="mt-10 flex flex-wrap gap-2">
                @foreach ($project->tags as $tag)
                    <span class="rounded-lg border border-linie px-3 py-1 text-sm text-text-leise">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

        @if ($project->posts->isNotEmpty())
            <section class="mt-16 border-t border-linie pt-10">
                <h2 class="mb-6 text-xl">Aus dem Blog</h2>
                <ul class="space-y-4">
                    @foreach ($project->posts as $beitrag)
                        <li>
                            <a href="{{ route('blog.show', $beitrag) }}"
                               class="group block rounded-xl border border-linie bg-karte p-4 transition-colors hover:border-akzent/40">
                                <span class="block text-sm text-text-leise">
                                    {{ $beitrag->published_at?->translatedFormat('d.m.Y') }}
                                </span>
                                <span class="mt-1 block transition-colors group-hover:text-akzent">
                                    {{ $beitrag->title }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

    </div>

</x-layouts.oeffentlich>
