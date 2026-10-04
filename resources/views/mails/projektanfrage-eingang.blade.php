<x-mail::message>
# Neue Projektanfrage

**Von:** {{ $anfrage->name }}@if (filled($anfrage->details['antworten']['firma'] ?? null)) ({{ $anfrage->details['antworten']['firma'] }})@endif<br>
**E-Mail:** {{ $anfrage->email }}@if (filled($anfrage->details['antworten']['telefon'] ?? null))<br>
**Telefon:** {{ $anfrage->details['antworten']['telefon'] }}@endif


---

@foreach ($anfrage->details['zusammenfassung'] as $zeile)
**{{ $zeile['frage'] }}**<br>
{{ $zeile['antwort'] }}

@endforeach
@if (filled($anfrage->details['antworten']['nachricht'] ?? null))
**Nachricht**<br>
{{ $anfrage->details['antworten']['nachricht'] }}

@endif
---

## Genannter Richtpreis

@forelse ($preisZeilen as $zeile)
**{{ $zeile['titel'] }}:** {{ $zeile['betrag'] }}<br>
{{ $zeile['zusatz'] }}

@empty
Kein Preis genannt – das Vorhaben ließ sich keiner Leistung zuordnen.

@endforelse
Der anfragenden Person wurde eine Rückmeldung innerhalb von 24 Stunden zugesagt.

Antworten geht direkt an {{ $anfrage->name }} – die Antwortadresse ist gesetzt.
</x-mail::message>
