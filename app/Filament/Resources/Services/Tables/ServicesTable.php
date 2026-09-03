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
                    ->label('Einmalig')
                    ->state(fn ($record) => $record->priceLabel() ?? 'auf Anfrage')
                    ->sortable(),

                // Zweite Spalte statt einer gemeinsamen: die beiden Preise
                // gehoeren zusammen, aber wer sie pflegt, muss auf einen Blick
                // sehen, wo noch kein Abo hinterlegt ist.
                TextColumn::make('monthly_price')
                    ->label('Abo')
                    ->state(fn ($record) => $record->aboLabel())
                    ->description(fn ($record) => $record->aboBedingungen())
                    ->placeholder('kein Abo')
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
