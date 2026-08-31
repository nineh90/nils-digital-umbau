<x-mail::message>
# Hallo {{ $absenderName }},

danke für deine Nachricht – sie ist angekommen. Wir melden uns in der Regel
innerhalb eines Werktags bei dir.

Zur Sicherheit hier noch einmal, was du geschrieben hast:

**Betreff:** {{ $betreff }}

> {{ $nachricht }}

Bis gleich
Dein Team von Nils-Digital

<x-mail::subcopy>
Diese Nachricht wurde automatisch verschickt. Du kannst einfach darauf antworten,
sie landet direkt bei uns.
</x-mail::subcopy>
</x-mail::message>
