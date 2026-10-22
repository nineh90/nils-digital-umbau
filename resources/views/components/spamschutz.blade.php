{{-- Fallen für Roboter, geprüft in App\Support\Spamschutz.

     Honigtopf: für Menschen unsichtbar, aber nicht per display:none – manche
     Roboter erkennen das. tabindex und autocomplete halten ihn aus der
     Tastaturbedienung und dem Ausfüllvorschlag heraus.

     Zeitstempel: wann diese Seite ausgeliefert wurde, verschlüsselt, damit
     ihn niemand vordatieren kann. --}}
<div class="absolute left-[-9999px]" aria-hidden="true">
    <label for="website">Website</label>
    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
</div>
<input type="hidden" name="{{ \App\Support\Spamschutz::FELD }}" value="{{ \App\Support\Spamschutz::stempel() }}">
