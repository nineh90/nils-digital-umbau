<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Projekt')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Titel')
                        ->required()
                        ->maxLength(120)
                        ->live(onBlur: true)
                        // Adresse nur beim Anlegen mitschreiben – siehe Hinweis
                        // am Feld darunter.
                        ->afterStateUpdated(function (string $operation, $state, callable $set) {
                            if ($operation === 'create') {
                                $set('slug', Str::slug((string) $state, '-', 'de'));
                            }
                        })
                        ->columnSpanFull(),

                    TextInput::make('slug')
                        ->label('Adresse')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->prefix('/projekte/')
                        ->helperText('Nach der Veröffentlichung nicht mehr ändern – bestehende Links laufen sonst ins Leere.')
                        ->columnSpanFull(),

                    TextInput::make('type')
                        ->label('Art')
                        ->maxLength(60)
                        ->placeholder('Kundenprojekt')
                        ->helperText('Steht klein über dem Titel auf der Kachel.'),

                    Select::make('status')
                        ->label('Stand')
                        ->options([
                            'live' => 'Live',
                            'beta' => 'Beta',
                            'planned' => 'Geplant',
                        ])
                        ->placeholder('ohne Angabe')
                        ->helperText('Erscheint als farbiges Fähnchen auf der Kachel.'),

                    Textarea::make('description')
                        ->label('Kurzbeschreibung')
                        ->required()
                        ->rows(3)
                        ->maxLength(300)
                        // Die Kachel schneidet nach drei Zeilen ab, und derselbe
                        // Text dient der Projektseite als Meta-Description.
                        ->helperText('Erscheint auf der Kachel und beim Teilen. Nach etwa drei Zeilen wird abgeschnitten.')
                        ->columnSpanFull(),

                    TagsInput::make('tags')
                        ->label('Schlagworte')
                        ->placeholder('Hinzufügen und Enter drücken')
                        ->helperText('Auf der Kachel werden die ersten vier gezeigt.')
                        ->columnSpanFull(),
                ]),

            Section::make('Bild')
                ->columns(2)
                ->schema([
                    FileUpload::make('image')
                        ->label('Screenshot')
                        ->image()
                        ->disk('inhalte')
                        ->directory('uploads/projekte')
                        ->visibility('public')
                        ->imagePreviewHeight('160')
                        ->helperText('Ohne Bild erscheint das Projekt nicht im Hero der Startseite.')
                        ->columnSpanFull(),

                    /*
                     * Das war ein Datei-Upload und dazu ein Pflichtfeld – in
                     * einer Spalte, die "cover" oder "contain" enthält. Beim
                     * Speichern verlangte das Formular deshalb ein Bild, das
                     * es hier gar nicht geben kann, und ein bestehendes
                     * Projekt liess sich nicht mehr sichern.
                     */
                    Select::make('image_fit')
                        ->label('Bildzuschnitt')
                        ->options([
                            'cover' => 'Füllend – richtig für Screenshots und Fotos',
                            'contain' => 'Einpassen – richtig für Logos',
                        ])
                        ->placeholder('Füllend (Vorgabe)'),
                ]),

            Section::make('Verlinkung')
                ->columns(2)
                ->schema([
                    TextInput::make('link')
                        ->label('Adresse des Projekts')
                        ->url()
                        ->placeholder('https://beispiel.de')
                        ->helperText('Steht in der Adresszeile des Browserfensters im Hero. Ohne sie erscheint das Projekt dort nicht.')
                        ->columnSpanFull(),

                    Toggle::make('is_internal')
                        ->label('Eigene Seite')
                        ->helperText('Aus: Der Link öffnet in einem neuen Tab, wie bei fremden Seiten üblich.'),
                ]),

            Section::make('Fallstudie')
                ->description('Ohne Text bekommt das Projekt keine eigene Unterseite und wird von Suchmaschinen übergangen. Das ist der grösste Hebel für die Sichtbarkeit.')
                ->schema([
                    MarkdownEditor::make('body')
                        ->label('Text')
                        ->toolbarButtons([
                            'bold', 'italic', 'heading', 'bulletList', 'orderedList',
                            'link', 'blockquote', 'undo', 'redo',
                        ])
                        ->columnSpanFull(),
                ]),

            Section::make('Anzeige')
                ->columns(2)
                ->schema([
                    /*
                     * Vorgabe an: ein frisch angelegtes Projekt soll auf der
                     * Startseite auftauchen, ohne dass man daran denken muss.
                     * Die Spalte selbst steht weiterhin auf false, das trifft
                     * nur den Import und nicht die Redaktion.
                     */
                    Toggle::make('is_featured')
                        ->label('Auf der Startseite zeigen')
                        ->default(true)
                        ->helperText('Im Hero erscheint das Projekt zusätzlich nur mit Bild und Adresse.'),

                    TextInput::make('position')
                        ->label('Reihenfolge')
                        ->numeric()
                        ->default(0)
                        ->helperText('Kleinere Zahl steht weiter vorn.'),
                ]),
        ]);
    }
}
