<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Support\Symbole;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Leistung')
                ->columns(2)
                ->schema([
                    Select::make('service_category_id')
                        ->label('Gruppe')
                        ->relationship('category', 'name')
                        ->required()
                        ->preload(),

                    Select::make('icon')
                        ->label('Symbol')
                        ->options(Symbole::auswahl())
                        ->searchable()
                        ->placeholder('ohne Symbol')
                        ->helperText('Strichzeichnung, die die Textfarbe erbt. Bewusst kein Emoji: das zeichnet jedes Betriebssystem anders.'),

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

                    Textarea::make('description')
                        ->label('Beschreibung')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),

                    TextInput::make('position')
                        ->label('Reihenfolge')
                        ->numeric()
                        ->default(0),
                ]),

            Section::make('Einmalpreis')
                ->description('Was die Leistung kostet, wenn sie auf einen Schlag bezahlt wird. Hosting und Pflege sind darin nicht enthalten und werden getrennt gebucht.')
                ->columns(2)
                ->schema([
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
                ]),

            Section::make('Abo')
                ->description('Dieselbe Leistung monatlich statt auf einmal. Bleibt der Monatspreis leer, erscheint die Leistung nur in der einmaligen Ansicht – richtig für alles, was ohnehin schon monatlich läuft oder nach Stunden abgerechnet wird.')
                ->columns(2)
                ->schema([
                    TextInput::make('monthly_price')
                        ->label('Monatspreis')
                        ->numeric()
                        ->prefix('€')
                        ->suffix('/ Monat')
                        ->helperText('Leer lassen, wenn es für diese Leistung kein Abo gibt.'),

                    TextInput::make('term_months')
                        ->label('Mindestlaufzeit')
                        ->numeric()
                        ->suffix('Monate')
                        ->helperText('Bei Verbrauchern wären höchstens 24 Monate zulässig. Die Angebote richten sich an Unternehmen – trotzdem ist alles über 24 Monaten schwer zu verkaufen.'),

                    TextInput::make('renewal_price')
                        ->label('Preis danach')
                        ->numeric()
                        ->prefix('€')
                        ->suffix('/ Monat')
                        ->helperText('Was nach der Mindestlaufzeit gilt, dann monatlich kündbar. Ohne diese Angabe sieht es aus, als liefe der Einstiegspreis ewig weiter.'),

                    TextInput::make('setup_fee')
                        ->label('Einrichtung einmalig')
                        ->numeric()
                        ->prefix('€')
                        ->helperText('Nur für größere Projekte, bei denen der Aufwand nicht vorfinanziert werden kann. Leer lassen heißt: kein Geld beim Start.'),

                    Textarea::make('subscription_includes')
                        ->label('Im Abo enthalten')
                        ->rows(3)
                        ->columnSpanFull()
                        ->helperText('Ein Satz, was über die Erstellung hinaus dabei ist – und in welchem Umfang. Ohne Grenze wird daraus stillschweigend unbegrenzter Service.'),
                ]),
        ]);
    }
}
