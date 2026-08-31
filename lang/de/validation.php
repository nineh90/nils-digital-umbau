<?php

/*
 * Deutsche Prüftexte.
 *
 * Ohne diese Datei erscheinen bei APP_LOCALE=de die rohen Schlüssel –
 * der Besucher läse dann "validation.email" statt eines Satzes.
 * APP_FALLBACK_LOCALE steht zusätzlich auf en, damit hier fehlende
 * Schlüssel wenigstens einen englischen Satz ergeben und nie einen Schlüssel.
 */
return [
    'accepted' => ':attribute muss akzeptiert werden.',
    'after' => ':attribute muss ein Datum nach :date sein.',
    'before' => ':attribute muss ein Datum vor :date sein.',
    'between' => [
        'array' => ':attribute muss zwischen :min und :max Elemente haben.',
        'file' => ':attribute muss zwischen :min und :max Kilobytes groß sein.',
        'numeric' => ':attribute muss zwischen :min und :max liegen.',
        'string' => ':attribute muss zwischen :min und :max Zeichen lang sein.',
    ],
    'boolean' => ':attribute muss wahr oder falsch sein.',
    'confirmed' => ':attribute stimmt nicht mit der Bestätigung überein.',
    'date' => ':attribute ist kein gültiges Datum.',
    'different' => ':attribute und :other müssen sich unterscheiden.',
    'email' => 'Bitte gib eine gültige E-Mail-Adresse an.',
    'file' => ':attribute muss eine Datei sein.',
    'filled' => ':attribute darf nicht leer sein.',
    'image' => ':attribute muss ein Bild sein.',
    'in' => 'Der gewählte Wert für :attribute ist ungültig.',
    'integer' => ':attribute muss eine ganze Zahl sein.',
    'max' => [
        'array' => ':attribute darf höchstens :max Elemente haben.',
        'file' => ':attribute darf höchstens :max Kilobytes groß sein.',
        'numeric' => ':attribute darf höchstens :max sein.',
        'string' => ':attribute darf höchstens :max Zeichen lang sein.',
    ],
    'mimes' => ':attribute muss eine Datei vom Typ :values sein.',
    'min' => [
        'array' => ':attribute muss mindestens :min Elemente haben.',
        'file' => ':attribute muss mindestens :min Kilobytes groß sein.',
        'numeric' => ':attribute muss mindestens :min sein.',
        'string' => ':attribute muss mindestens :min Zeichen lang sein.',
    ],
    'numeric' => ':attribute muss eine Zahl sein.',
    'prohibited' => ':attribute ist nicht erlaubt.',
    'required' => 'Bitte fülle das Feld :attribute aus.',
    'string' => ':attribute muss Text sein.',
    'unique' => ':attribute ist bereits vergeben.',
    'url' => ':attribute muss eine gültige Adresse sein.',
    'uploaded' => ':attribute konnte nicht hochgeladen werden.',

    'custom' => [],
    'attributes' => [],
];
