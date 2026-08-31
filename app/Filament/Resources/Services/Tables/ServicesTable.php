<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('icon')->label('')->width(40),

                TextColumn::make('name')
                    ->label('Leistung')
                    ->searchable()
                    ->description(fn ($record) => \Illuminate\Support\Str::limit($record->description, 80)),

                TextColumn::make('category.name')
                    ->label('Gruppe')
                    ->badge()
                    ->sortable(),

                // Formatierung wie auf der Seite, damit im Backend sofort
                // sichtbar ist, was der Besucher zu lesen bekommt.
                TextColumn::make('price')
                    ->label('Preis')
                    ->formatStateUsing(fn ($record) => $record->priceLabel() ?? 'auf Anfrage')
                    ->sortable(),

                TextColumn::make('position')->label('Reihenfolge')->sortable(),
            ])
            ->defaultSort('position')
            ->groups(['category.name'])
            ->filters([
                SelectFilter::make('service_category_id')
                    ->label('Gruppe')
                    ->relationship('category', 'name')
                    ->preload(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
