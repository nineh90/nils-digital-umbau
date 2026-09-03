<?php

namespace App\Filament\Resources\Notices\Schemas;

use App\Models\Notice;
use App\Support\Symbole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NoticeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Was drinsteht')
                ->schema([
                    TextInput::make('title')
                        ->label('Überschrift')
                        ->required()
                        ->maxLength(80)
                        ->helperText('Kurz. Wer ein Fenster wegklickt, liest höchstens diese Zeile.'),

                    Textarea::make('body')
                        ->label('Text')
                        ->required()
                        ->rows(3)
                        ->maxLength(300)
                        ->helperText('Ein bis zwei Sätze. Alles Längere gehört auf eine Seite, auf die der Knopf führt.'),

                    Select::make('icon')
                        ->label('Symbol')
                        ->options(Symbole::auswahl())
                        ->searchable()
                        ->placeholder('ohne Symbol'),

                    FileUpload::make('image')
                        ->label('Bild')
                        ->image()
                        ->disk('inhalte')
                        ->directory('hinweise')
                        ->helperText('Optional, und nur bei „Mitte“ und „Ecke“ sichtbar – in der Leiste oben ist kein Platz dafür.'),
                ]),

            Section::make('Knopf')
                ->description('Beides ausfüllen oder beides leer lassen. Ein Hinweis ohne Ziel ist auch in Ordnung.')
                ->columns(2)
                ->schema([
                    TextInput::make('button_label')
                        ->label('Beschriftung')
                        ->maxLength(40)
                        ->requiredWith('button_url')
                        ->placeholder('Zum Angebot'),

                    TextInput::make('button_url')
                        ->label('Ziel')
                        ->url()
                        ->requiredWith('button_label')
                        ->placeholder('https://…  oder  /leistungen'),
                ]),

            Section::make('Aussehen')
                ->columns(2)
                ->schema([
                    Radio::make('placement')
                        ->label('Darstellung')
                        ->options(Notice::darstellungen())
                        ->default('center')
                        ->required(),

                    Radio::make('scheme')
                        ->label('Farbschema')
                        ->options(Notice::schemata())
                        ->default('akzent')
                        ->required()
                        ->helperText('Alle Schemata kommen aus den Farben der Seite – es kann also nichts danebengehen.'),
                ]),

            Section::make('Wann und wie oft')
                ->columns(2)
                ->schema([
                    DateTimePicker::make('starts_at')
                        ->label('Ab')
                        ->seconds(false)
                        ->helperText('Leer heißt: sofort, sobald eingeschaltet.'),

                    DateTimePicker::make('ends_at')
                        ->label('Bis')
                        ->seconds(false)
                        ->after('starts_at')
                        ->helperText('Leer heißt: bis du ihn abschaltest. Für Weihnachten hier den 27.12. eintragen und nicht mehr daran denken.'),

                    Radio::make('frequency')
                        ->label('Häufigkeit')
                        ->options(Notice::haeufigkeiten())
                        ->default('once')
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('position')
                        ->label('Reihenfolge')
                        ->numeric()
                        ->default(0)
                        ->helperText('Gelten mehrere gleichzeitig, erscheint der mit der kleinsten Zahl. Es wird immer nur einer gezeigt.'),

                    Toggle::make('is_active')
                        ->label('Eingeschaltet')
                        ->helperText('Solange das aus ist, sieht ihn niemand – egal, was im Zeitraum steht.'),
                ]),
        ]);
    }
}
