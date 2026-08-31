@props([
    'titel',
    'beschreibung' => null,
    'bild' => null,
    'typ' => 'website',
    'kanonisch' => null,
    'robots' => null,
    'jsonld' => null,
])

{{--
    Grundgerüst aller öffentlichen Seiten.

    Ersetzt das alte legacy/js/include.js: dort wurden Kopf- und Fußzeile per
    fetch() nachgeladen, die Navigationslinks trugen statt href ein data-path,
    das nachträglich umgeschrieben wurde, und alles, was den Header brauchte,
    musste auf ein eigenes headerLoaded-Ereignis warten. Als Blade-Layout fällt
    diese ganze Mechanik weg – und die Seite funktioniert auch ohne JavaScript.
--}}

<!DOCTYPE html>
<html lang="de" class="scroll-pt-20">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <x-seo
        :titel="$titel"
        :beschreibung="$beschreibung"
        :bild="$bild"
        :typ="$typ"
        :kanonisch="$kanonisch"
        :robots="$robots"
        :jsonld="$jsonld"
    />

    <link rel="icon" href="/assets/images/logo/logo.png">
    <link rel="alternate" type="application/rss+xml" title="Blog von Nils-Digital" href="{{ route('blog.feed') }}">

    {{-- Selbst gehostete Schriften (Fredoka für Überschriften, Roboto Mono für
         den Text). Vite::fonts() setzt die Preload-Links und die @font-face-
         Regeln – ohne diesen Aufruf werden die Dateien zwar gebaut, aber nie
         eingebunden, und die Seite fällt still auf die Systemschrift zurück. --}}
    {{ Vite::fonts() }}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    {{-- Sprunglink: erste Station für alle, die mit der Tastatur navigieren. --}}
    <a href="#inhalt"
       class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50
              focus:rounded-lg focus:bg-akzent focus:px-4 focus:py-2 focus:text-flaeche focus:font-medium">
        Zum Inhalt springen
    </a>

    <x-kopfzeile />

    <main id="inhalt" class="min-h-[60vh]">
        {{ $slot }}
    </main>

    <x-fusszeile />

</body>
</html>
