{{-- Die XML-Deklaration wird zusammengesetzt statt direkt geschrieben.
     Steht "<?xml" wortwoertlich in der Vorlage, laesst Blade die Zeile bei
     eingeschaltetem short_open_tag unkompiliert stehen und PHP bricht ab. --}}
@php echo '<'.'?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL @endphp
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>Blog von Nils-Digital</title>
        <link>{{ route('blog.index') }}</link>
        <atom:link href="{{ route('blog.feed') }}" rel="self" type="application/rss+xml"/>
        <description>Beiträge zu Webentwicklung, KI-Automatisierung und Kundenprojekten.</description>
        <language>de-DE</language>
        @if ($beitraege->isNotEmpty())
            <lastBuildDate>{{ $beitraege->first()->published_at?->toRfc2822String() }}</lastBuildDate>
        @endif
        @foreach ($beitraege as $beitrag)
            <item>
                <title>{{ $beitrag->title }}</title>
                <link>{{ route('blog.show', $beitrag) }}</link>
                <guid isPermaLink="true">{{ route('blog.show', $beitrag) }}</guid>
                <pubDate>{{ $beitrag->published_at?->toRfc2822String() }}</pubDate>
                <description>{{ $beitrag->teaser }}</description>
                @if ($beitrag->category)
                    <category>{{ $beitrag->category->name }}</category>
                @endif
            </item>
        @endforeach
    </channel>
</rss>
