<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('type'),
                TextInput::make('status'),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('body')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                FileUpload::make('image_fit')
                    ->image()
                    ->required(),
                TextInput::make('link'),
                Toggle::make('is_internal')
                    ->required(),
                /*
                 * Vorgabe an: ein frisch angelegtes Projekt soll auf der
                 * Startseite auftauchen, ohne dass man daran denken muss.
                 * Der Schalter bleibt, damit man es abwählen kann – die
                 * Spalte selbst steht weiterhin auf false, das trifft nur
                 * den Import und nicht die Redaktion.
                 */
                Toggle::make('is_featured')
                    ->label('Auf der Startseite zeigen')
                    ->default(true)
                    ->helperText('Im Hero erscheint das Projekt zusätzlich nur, wenn Bild und Adresse hinterlegt sind – ohne beides gäbe es kein Browserfenster zu zeigen.'),
                Textarea::make('tags')
                    ->columnSpanFull(),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
