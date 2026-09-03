<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kundenstimme')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(80)
                        ->helperText('So, wie er auf der Seite stehen soll – etwa „Björn R.“.'),

                    Select::make('rating')
                        ->label('Sterne')
                        ->options([5 => '5', 4 => '4', 3 => '3', 2 => '2', 1 => '1'])
                        ->default(5)
                        ->helperText('Zählt für die Gesamtbewertung auf der Startseite.'),

                    /*
                     * Ausdrücklich kein Pflichtfeld: bei Google vergeben die
                     * meisten nur Sterne. Solche Stimmen zählen für den
                     * Schnitt mit und erscheinen nur nicht als Zitat – dafür
                     * gibt es Review::vorzeigbar(). Das Feld verlangte den
                     * Text bisher, eine reine Sternebewertung liess sich damit
                     * gar nicht anlegen.
                     */
                    Textarea::make('text')
                        ->label('Text')
                        ->rows(4)
                        ->helperText('Darf leer bleiben. Ohne Text zählt die Bewertung für den Schnitt, erscheint aber nicht als Zitat.')
                        ->columnSpanFull(),

                    TextInput::make('source')
                        ->label('Herkunft')
                        ->maxLength(60)
                        ->placeholder('Google')
                        ->helperText('Steht klein unter dem Namen.'),

                    TextInput::make('project')
                        ->label('Projekt')
                        ->maxLength(80)
                        ->helperText('Freitext, worum es ging. Keine Verknüpfung zur Projektliste.'),
                ]),

            Section::make('Anzeige')
                ->columns(2)
                ->schema([
                    Toggle::make('is_visible')
                        ->label('Auf der Seite zeigen')
                        ->default(true),

                    TextInput::make('position')
                        ->label('Reihenfolge')
                        ->numeric()
                        ->default(0)
                        ->helperText('Die Startseite zeigt vier zufällige – die Reihenfolge greift überall sonst.'),
                ]),
        ]);
    }
}
