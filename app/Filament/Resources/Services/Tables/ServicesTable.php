<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->reorderable('position')
            // Nach Gruppe zusammengefasst: die Leistungsseite zeigt sie genauso,
            // und ohne die Bündelung steht hier eine Liste aus fünfzehn Zeilen
            // ohne erkennbaren Zusammenhang.
            ->groups(['category.name'])
            ->defaultGroup('category.name')
            ->columns([
                TextColumn::make('name')
                    ->label('Leistung')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('icon')
                    ->label('Symbol')
                    ->badge()
                    ->color('gray')
                    ->placeholder('ohne'),

                TextColumn::make('price')
                    ->label('Preis')
                    ->state(fn ($record) => $record->priceLabel() ?? 'auf Anfrage')
                    ->sortable(),
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
