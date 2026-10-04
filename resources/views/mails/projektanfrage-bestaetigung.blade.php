<x-mail::message>
# Hallo {{ $anfrage->name }},

danke für deine Projektanfrage – sie ist angekommen. Wir melden uns innerhalb
von 24 Stunden persönlich bei dir.

@if ($preisZeilen !== [])
## Dein Richtpreis

@foreach ($preisZeilen as $zeile)
**{{ $zeile['titel'] }}:** {{ $zeile['betrag'] }}<br>
{{ $zeile['zusatz'] }}

@endforeach
Das ist ein unverbindlicher Richtwert auf Grundlage deiner Angaben. Das
verbindliche Angebot bekommst du von uns, sobald wir dein Vorhaben kennen.
Es gilt die Kleinunternehmerregelung nach § 19 UStG, die Preise enthalten
daher keine Umsatzsteuer.
@else
Einen Richtpreis können wir dir für dein Vorhaben noch nicht nennen – dafür
müssen wir erst verstehen, worum es geht. Genau das klären wir im Gespräch.
@endif

## Deine Angaben

@foreach ($anfrage->details['zusammenfassung'] as $zeile)
**{{ $zeile['frage'] }}**<br>
{{ $zeile['antwort'] }}

@endforeach
Bis gleich<br>
Dein Team von Nils-Digital

<x-mail::subcopy>
Diese Nachricht wurde automatisch verschickt. Du kannst einfach darauf antworten,
sie landet direkt bei uns.
</x-mail::subcopy>
</x-mail::message>
