@php
    $istKategorie = $aktiveKategorie !== null;

    $titel = $istKategorie
        ? $aktiveKategorie->name.' – Blog'
        : 'Blog – Webentwicklung, KI-Automatisierung und Projekte';

    $beschreibung = $istKategorie
        ? "Alle Beiträge aus der Kategorie {$aktiveKategorie->name} von Nils-Digital."
        : 'Beiträge zu Webentwicklung, KI-Automatisierung, eigenen Produkten und Kundenprojekten – aus der Praxis von Nils-Digital.';

    // Ab Seite 2 nicht indexieren: die Übersichtsseiten unterscheiden sich nur
    // in der Reihenfolge derselben Teaser und stehen sonst in Konkurrenz zu den
    // Beiträgen selbst.
    $robots = $beitraege->currentPage() > 1 ? 'noindex, follow' : null;

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type' => 'Blog',
        'name' => 'Blog von Nils-Digital',
        'url' => route('blog.index'),
        'inLanguage' => 'de-DE',
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Nils-Digital',
            'url' => url('/'),
        ],
        'blogPost' => $beitraege->map(fn ($b) => [
            '@type' => 'BlogPosting',
            'headline' => $b->title,
            'url' => route('blog.show', $b),
            'datePublished' => $b->published_at?->toDateString(),
        ])->all(),
    ];
@endphp

<x-layouts.oeffentlich :titel="$titel" :beschreibung="$beschreibung" :robots="$robots" :jsonld="$jsonld">

    {{-- Derselbe Kopf wie auf allen Unterseiten; die Kategorieansicht
         wechselt nur Überschrift und Text.

         An die Stelle des früheren Pfads „Blog / Kategorie" tritt dabei der
         Knopf zurück zur Übersicht: er führt dorthin, wohin der Pfad geführt
         hätte, und passt in die Zeile, die auf den anderen Seiten die
         Handlungsaufforderung trägt. --}}
    <x-seitenkopf
        :ueberschrift="$istKategorie ? $aktiveKategorie->name : 'Blog'"
        :text="$istKategorie
            ? trans_choice(':count Beitrag|:count Beiträge', $beitraege->total(), ['count' => $beitraege->total()]) . ' in dieser Kategorie.'
            : 'Was wir bauen, warum wir es so bauen und was dabei schiefgeht. Keine Pressemitteilungen.'">

        @if ($istKategorie)
            <x-slot:aktionen>
                <a href="{{ route('blog.index') }}"
                   class="rounded-lg border border-linie px-5 py-2.5 transition-colors hover:border-akzent hover:text-akzent">
                    Alle Beiträge
                </a>
            </x-slot:aktionen>
        @endif
    </x-seitenkopf>

    <div class="mx-auto max-w-6xl px-5 py-14">

        {{-- Kategoriefilter. Echte Links statt JavaScript-Filter: jede
             Kategorie ist damit eine eigene, indexierbare Seite. --}}
        <nav aria-label="Kategorien" class="mb-10 flex flex-wrap gap-2">
            <a href="{{ route('blog.index') }}"
               @class([
                   'rounded-full border px-3.5 py-1.5 text-sm transition-colors',
                   'border-akzent text-akzent' => ! $istKategorie,
                   'border-linie text-text-leise hover:border-akzent/50 hover:text-text' => $istKategorie,
               ])>
                Alle
            </a>
            @foreach ($kategorien as $kategorie)
                <a href="{{ route('blog.kategorie', $kategorie) }}"
                   @class([
                       'rounded-full border px-3.5 py-1.5 text-sm transition-colors',
                       'border-akzent text-akzent' => $istKategorie && $aktiveKategorie->is($kategorie),
                       'border-linie text-text-leise hover:border-akzent/50 hover:text-text' => ! ($istKategorie && $aktiveKategorie->is($kategorie)),
                   ])>
                    {{ $kategorie->name }}
                    <span class="text-text-leise">{{ $kategorie->posts_count }}</span>
                </a>
            @endforeach
        </nav>

        @if ($beitraege->isEmpty())
            <p class="text-text-leise">Hier steht noch nichts.</p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($beitraege as $i => $beitrag)
                    <div data-auftritt="{{ $i }}">
                        <x-beitragskachel :beitrag="$beitrag" />
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $beitraege->links() }}
            </div>
        @endif

    </div>

</x-layouts.oeffentlich>
