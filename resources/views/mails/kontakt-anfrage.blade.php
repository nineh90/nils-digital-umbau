<x-mail::message>
# Neue Anfrage über die Website

**Von:** {{ $absenderName }}
**E-Mail:** {{ $absenderMail }}
**Betreff:** {{ $betreff }}

---

{{ $nachricht }}

---

Antworten geht direkt an {{ $absenderName }} – die Antwortadresse ist gesetzt.
</x-mail::message>
