<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Prüfung des Kontaktformulars.
 *
 * Feldnamen bewusst identisch zum alten backend/contact.php. Honigtopf und
 * Zeitstempel prüft nicht diese Klasse, sondern App\Support\Spamschutz – ein
 * Roboter soll keine Fehlermeldung zu sehen bekommen.
 */
class KontaktRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'E-Mail-Adresse',
            'subject' => 'Betreff',
            'message' => 'Nachricht',
        ];
    }

    public function messages(): array
    {
        return [
            'message.min' => 'Schreib bitte ein paar Sätze mehr, damit ich dir sinnvoll antworten kann.',
        ];
    }
}
