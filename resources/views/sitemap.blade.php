{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($eintraege as $eintrag)
    <url>
        <loc>{{ $eintrag['url'] }}</loc>
@if ($eintrag['datum'])
        <lastmod>{{ $eintrag['datum']->toDateString() }}</lastmod>
@endif
        <changefreq>{{ $eintrag['frequenz'] }}</changefreq>
        <priority>{{ $eintrag['gewicht'] }}</priority>
    </url>
@endforeach
</urlset>
