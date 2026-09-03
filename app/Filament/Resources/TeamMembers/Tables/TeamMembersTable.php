<?php

namespace App\Filament\Resources\TeamMembers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TeamMembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            // Ziehen statt Zahlen tippen: bei zwei, drei Personen ist die
            // Reihenfolge genau das, was man aendern will.
            ->reorderable('position')
            ->columns([
                ImageColumn::make('image')
                    ->label('Foto')
                    ->disk('inhalte')
                    ->circular()
                    ->defaultImageUrl(null),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Rolle')
                    ->searchable(),

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
