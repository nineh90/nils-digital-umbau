<?php

namespace App\Filament\Resources\Reviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->reorderable('position')
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('rating')
                    ->label('Sterne')
                    ->formatStateUsing(fn (?int $state) => $state ? str_repeat('★', $state) : '–')
                    ->color('warning')
                    ->sortable(),

                /*
                 * Nicht der Text selbst, sondern ob es einen gibt: genau das
                 * entscheidet, ob die Stimme als Zitat erscheint oder nur für
                 * den Schnitt zählt.
                 */
                TextColumn::make('text')
                    ->label('Zitat')
                    ->badge()
                    ->state(fn ($record) => filled($record->text) ? 'mit Text' : 'nur Sterne')
                    ->color(fn ($state) => $state === 'mit Text' ? 'success' : 'gray'),

                TextColumn::make('source')
                    ->label('Herkunft')
                    ->searchable()
                    ->toggleable(),

                ToggleColumn::make('is_visible')
                    ->label('Sichtbar'),
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
