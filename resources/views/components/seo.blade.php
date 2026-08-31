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
    Serverseitiges SEO.

    Das ist der Kern des ganzen Umbaus. Auf der alten Seite standen in
    pages/blog-post.html leere Meta-Tags, die post.js -> setSeoDynamic() erst im
    Browser befüllte. Crawler von WhatsApp, LinkedIn und Facebook führen kein
    JavaScript aus – jeder geteilte Beitrag zeigte deshalb seit jeher
    "Blog – nils-digital" ohne Bild. Auch das canonical-Tag war im ausgelieferten
    HTML leer.

    Hier steht alles im HTML, bevor es den Server verlässt.
--}}

@php
    $kanonisch ??= url()->current();
    $beschreibung = $beschreibung ? \Illuminate\Support\Str::limit(strip_tags($beschreibung), 160) : null;
    $bild = $bild ? url($bild) : url('assets/images/logo/logo.png');
    $seitenTitel = $titel === config('app.name') ? $titel : $titel.' – '.config('app.name');
@endphp

<title>{{ $seitenTitel }}</title>
<link rel="canonical" href="{{ $kanonisch }}">

@if ($beschreibung)
    <meta name="description" content="{{ $beschreibung }}">
@endif

@if ($robots)
    <meta name="robots" content="{{ $robots }}">
@endif

<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ $titel }}">
<meta property="og:type" content="{{ $typ }}">
<meta property="og:url" content="{{ $kanonisch }}">
<meta property="og:locale" content="de_DE">
<meta property="og:image" content="{{ $bild }}">
@if ($beschreibung)
    <meta property="og:description" content="{{ $beschreibung }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $titel }}">
<meta name="twitter:image" content="{{ $bild }}">
@if ($beschreibung)
    <meta name="twitter:description" content="{{ $beschreibung }}">
@endif

@if ($jsonld)
    {{-- JSON_UNESCAPED_SLASHES/UNICODE: sonst stehen Umlaute als ü und
         jeder Schrägstrich in den URLs maskiert im Quelltext. --}}
    <script type="application/ld+json">{!! json_encode($jsonld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) !!}</script>
@endif
