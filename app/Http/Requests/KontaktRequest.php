<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Prüfung des Kontaktformulars.
 *
 * Feldnamen bewusst identisch zum alten backend/contact.php – auch das
 * Honigtopf-Feld "website", das für Menschen unsichtbar ist und nur von
 * Formular-Robotern ausgefüllt wird.
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
            'website' => ['nullable', 'prohibited'],
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
            'website.prohibited' => 'Deine Anfrage konnte nicht gesendet werden.',
            'message.min' => 'Schreib bitte ein paar Sätze mehr, damit ich dir sinnvoll antworten kann.',
        ];
    }
}
