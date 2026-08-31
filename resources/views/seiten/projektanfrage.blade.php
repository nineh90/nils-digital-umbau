<x-layouts.oeffentlich
    titel="Projektanfrage"
    beschreibung="Beschreib dein Projekt im Fragebogen – Ziel, Umfang, Zeitrahmen. Danach melden wir uns mit einer Einschätzung.">

    <x-seitenkopf
        ueberschrift="Projektanfrage"
        text="Ein paar Fragen zu deinem Vorhaben – Ziel, Umfang, Zeitrahmen. Damit können wir dir gleich eine belastbare Einschätzung geben statt einer Rückfrage-Schleife." />

    <div class="mx-auto max-w-3xl px-5 py-14">
        <x-einbettung
            quelle="https://docs.google.com/forms/d/e/1FAIpQLSd-EFyDKc1vizclZKvuuhmoFJg13Jnbls4BPM_qXBFJZUO8Yw/viewform?embedded=true"
            direktlink="https://docs.google.com/forms/d/e/1FAIpQLSd-EFyDKc1vizclZKvuuhmoFJg13Jnbls4BPM_qXBFJZUO8Yw/viewform"
            titel="Projektfragebogen"
            anbieter="Google"
            hoehe="1600px" />

        <p class="mt-8 text-center text-sm text-text-leise">
            Lieber formlos? Dann schreib uns einfach über das
            <a href="{{ route('kontakt') }}" class="text-akzent hover:underline">Kontaktformular</a>.
        </p>
    </div>

</x-layouts.oeffentlich>
