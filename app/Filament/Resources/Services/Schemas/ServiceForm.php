<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Support\Symbole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('service_category_id')
                ->label('Gruppe')
                ->relationship('category', 'name')
                ->required()
                ->preload(),

            TextInput::make('name')
                ->label('Leistung')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create'
                    ? $set('slug', Str::slug((string) $state, '-', 'de'))
                    : null),

            TextInput::make('slug')
                ->label('Kennung')
                ->required()
                ->helperText('Interne Kennung, taucht nicht auf der Seite auf.'),

            Select::make('icon')
                ->label('Symbol')
                ->options(Symbole::auswahl())
                ->searchable()
                ->placeholder('ohne Symbol')
                ->helperText('Strichzeichnung, die die Textfarbe erbt. Bewusst kein Emoji: das zeichnet jedes Betriebssystem anders.'),

            TextInput::make('price')
                ->label('Preis')
                ->numeric()
                ->prefix('€')
                ->helperText('Leer lassen für „auf Anfrage“.'),

            Select::make('unit')
                ->label('Preisangabe')
                ->options([
                    'eur-ab' => 'ab … €',
                    'eur-pro-monat' => '… € pro Monat',
                    'eur-pro-stunde' => '… € pro Stunde',
                ])
                ->helperText('Bestimmt, wie der Preis auf der Seite formuliert wird.'),

            TextInput::make('position')
                ->label('Reihenfolge')
                ->numeric()
                ->default(0),

            Textarea::make('description')
                ->label('Beschreibung')
                ->required()
                ->rows(4)
                ->columnSpanFull(),
        ])->columns(2);
    }
}
