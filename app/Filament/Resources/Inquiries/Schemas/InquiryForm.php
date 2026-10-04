<?php

namespace App\Filament\Resources\Inquiries\Schemas;

use App\Models\Inquiry;
use App\Support\Fragebogen;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InquiryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            /*
             * Alles hier ist gesperrt. Was jemand geschrieben hat, wird nicht
             * nachträglich geändert – auch nicht versehentlich beim Speichern
             * des Status.
             */
            Section::make('Anfrage')
                ->description(fn (?Inquiry $record) => $record
                    ? 'Eingegangen am '.$record->created_at->format('d.m.Y \u\m H:i').' Uhr über '
                        .(Inquiry::HERKUNFT[$record->type] ?? $record->type).'.'
                    : null)
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->disabled(),

                    TextInput::make('email')
                        ->label('E-Mail-Adresse')
                        ->disabled(),

                    TextInput::make('subject')
                        ->label('Betreff')
                        ->disabled()
                        ->columnSpanFull(),

                    Textarea::make('message')
                        ->label('Nachricht')
                        ->disabled()
                        ->autosize()
                        ->columnSpanFull(),

                    /*
                     * Was der anfragenden Person am Ende des Fragebogens als
                     * Preis genannt wurde. Aus dem Datensatz und nicht neu
                     * gerechnet: steht hier eine andere Zahl als in ihrer
                     * Mail, beginnt das Gespräch mit einer Rückfrage.
                     */
                    Textarea::make('richtpreis')
                        ->label('Genannter Richtpreis')
                        ->disabled()
                        ->dehydrated(false)
                        ->autosize()
                        ->columnSpanFull()
                        ->visible(fn (?Inquiry $record) => $record?->type === 'projektanfrage')
                        ->afterStateHydrated(function (Textarea $component, ?Inquiry $record) {
                            $zeilen = Fragebogen::preisZeilen($record?->details['preis'] ?? [
                                'monatlich' => null, 'festpreis' => null, 'zahlweise' => 'offen',
                            ]);

                            $component->state($zeilen === []
                                ? 'Kein Preis genannt – das Vorhaben ließ sich keiner Leistung zuordnen.'
                                : collect($zeilen)->map(fn ($z) => $z['titel'].': '.$z['betrag'].' – '.$z['zusatz'])->implode("\n"));
                        }),
                ]),

            Section::make('Bearbeitung')
                ->schema([
                    Select::make('status')
                        ->label('Stand')
                        ->options(Inquiry::STATUS)
                        ->required()
                        ->native(false)
                        ->selectablePlaceholder(false),

                    Textarea::make('note')
                        ->label('Notiz')
                        ->rows(4)
                        ->helperText('Nur für dich. Die anfragende Person sieht das nicht.'),
                ]),
        ]);
    }
}
