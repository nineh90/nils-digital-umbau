<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kategorie')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(60)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create'
                            ? $set('slug', Str::slug((string) $state, '-', 'de'))
                            : null),

                    TextInput::make('slug')
                        ->label('Adresse')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->prefix('/blog/kategorie/')
                        ->helperText('Nach der Veröffentlichung nicht mehr ändern.'),
                ]),

            /*
             * Die Farben stehen als Spalte am Datensatz und nicht mehr als
             * .cat-…-Regel im Stylesheet. Eine neue Kategorie braucht deshalb
             * keine CSS-Änderung mehr – das war beim Umbau ausdrücklich Ziel.
             */
            Section::make('Fähnchen')
                ->description('Die Farbe des kleinen Etiketts auf den Beitragskacheln.')
                ->columns(2)
                ->schema([
                    ColorPicker::make('color')
                        ->label('Hintergrund')
                        ->helperText('Leer lassen für ein neutrales Grau.'),

                    ColorPicker::make('text_color')
                        ->label('Schrift')
                        ->helperText('Leer lassen für Weiß.'),
                ]),

            TextInput::make('position')
                ->label('Reihenfolge')
                ->numeric()
                ->default(0)
                ->helperText('Der Filter über der Blogübersicht sortiert allerdings nach Namen.'),
        ]);
    }
}
