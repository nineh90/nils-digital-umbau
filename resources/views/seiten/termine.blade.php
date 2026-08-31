<x-layouts.oeffentlich
    titel="Termin buchen"
    beschreibung="Buch dir ein kostenloses Videogespräch – unverbindlich, ohne Verkaufsdruck.">

    <x-seitenkopf
        ueberschrift="Termin buchen"
        text="Ein kostenloses Videogespräch, unverbindlich. Wir schauen uns gemeinsam an, was du vorhast, und sagen dir ehrlich, ob und wie wir helfen können." />

    <div class="mx-auto max-w-3xl px-5 py-14">
        <x-einbettung
            quelle="https://calendar.google.com/calendar/appointments/schedules/AcZssZ3pGJltqztrDmyHNKQTCl6r1S2wLVIPXX182MaNogWt4G6ALG7Fm7Olp_AAiO_ckdv4ZGa2bJws?gv=true"
            titel="Terminkalender"
            anbieter="Google"
            hoehe="800px" />

        <p class="mt-8 text-center text-sm text-text-leise">
            Passt kein Termin? Schreib uns über das
            <a href="{{ route('kontakt') }}" class="text-akzent hover:underline">Kontaktformular</a>,
            dann finden wir einen.
        </p>
    </div>

</x-layouts.oeffentlich>
