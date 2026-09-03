<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Person')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(100),

                    TextInput::make('role')
                        ->label('Rolle')
                        ->required()
                        ->maxLength(100)
                        ->helperText('Steht in der Akzentfarbe unter dem Namen, etwa „Entwickler".'),

                    FileUpload::make('image')
                        ->label('Foto')
                        ->image()
                        ->disk('inhalte')
                        ->directory('uploads/team')
                        ->visibility('public')
                        ->imagePreviewHeight('160')
                        ->helperText('Hochformat oder quadratisch. Ohne Foto zeigt die Karte den Anfangsbuchstaben des Namens.')
                        ->columnSpanFull(),

                    Textarea::make('bio')
                        ->label('Vorstellungstext')
                        ->required()
                        ->rows(5)
                        ->columnSpanFull(),

                    TagsInput::make('skills')
                        ->label('Schlagworte')
                        ->placeholder('Hinzufügen und Enter drücken')
                        ->helperText('Erscheinen als kleine Kästchen unter dem Text.')
                        ->columnSpanFull(),
                ]),

            Section::make('Hervorgehobener Satz')
                ->description('Der Satz mit dem Akzentstrich am Ende der Karte. Bleibt beides leer, entfällt er.')
                ->columns(2)
                ->schema([
                    TextInput::make('highlight_label')
                        ->label('Beschriftung')
                        ->maxLength(40)
                        ->placeholder('Arbeitsweise'),

                    TextInput::make('highlight_text')
                        ->label('Text')
                        ->maxLength(300),
                ]),

            Section::make('Anzeige')
                ->columns(2)
                ->schema([
                    Toggle::make('is_visible')
                        ->label('Auf der Seite zeigen')
                        ->default(true),

                    Toggle::make('in_schema')
                        ->label('Als Mitarbeiter an Suchmaschinen melden')
                        ->default(true)
                        ->helperText('Trägt den Eintrag in die strukturierten Daten der Seite ein. Für alles, was kein Mensch ist, ausschalten – Google nimmt einen jobTitle wörtlich.'),

                    TextInput::make('position')
                        ->label('Reihenfolge')
                        ->numeric()
                        ->default(0)
                        ->helperText('Kleinere Zahl steht weiter oben.'),
                ]),
        ]);
    }
}
