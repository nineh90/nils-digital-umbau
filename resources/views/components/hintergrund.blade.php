{{--
    Hintergrundebene.

    Liegt fest hinter dem gesamten Inhalt und gilt für alle Seiten – deshalb
    steht sie im Layout und nicht in einzelnen Views. Die Gestaltung dazu
    komplett in app.css unter "Hintergrundebene".

    aria-hidden, weil hier nichts steht, das jemand vorgelesen bekommen möchte.
--}}

<div class="hintergrund" aria-hidden="true">
    <div class="hintergrund__raster"></div>
    <div class="hintergrund__schleier hintergrund__schleier--eins"></div>
    <div class="hintergrund__schleier hintergrund__schleier--zwei"></div>
</div>
