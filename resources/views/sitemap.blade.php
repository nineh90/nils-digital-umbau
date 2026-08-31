{{-- Die XML-Deklaration wird zusammengesetzt statt direkt geschrieben.
     Steht "<?xml" wortwoertlich in der Vorlage, laesst Blade die Zeile bei
     eingeschaltetem short_open_tag unkompiliert stehen und PHP bricht ab. --}}
@php echo '<'.'?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL @endphp
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
