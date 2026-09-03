<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            // Ziehen statt Zahlen tippen: die Reihenfolge entscheidet, welche
            // drei Projekte auf der Startseite stehen.
            ->reorderable('position')
            ->columns([
                ImageColumn::make('image')
                    ->label('Bild')
                    ->disk('inhalte'),

                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Art')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Stand')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'live' => 'Live',
                        'beta' => 'Beta',
                        'planned' => 'Geplant',
                        default => '–',
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'live' => 'success',
                        'beta' => 'warning',
                        default => 'gray',
                    }),

                /*
                 * Zeigt auf einen Blick, welche Projekte eine eigene Unterseite
                 * haben. Ohne Fallstudie steht die Detailseite auf noindex –
                 * das sieht man der Liste sonst nicht an.
                 */
                TextColumn::make('body')
                    ->label('Fallstudie')
                    ->badge()
                    ->state(fn ($record) => filled($record->body) ? 'vorhanden' : 'fehlt')
                    ->color(fn ($state) => $state === 'vorhanden' ? 'success' : 'danger'),

                ToggleColumn::make('is_featured')
                    ->label('Startseite'),
            ])
            ->filters([
                TernaryFilter::make('is_featured')->label('Auf der Startseite'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
