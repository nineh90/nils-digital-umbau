<?php

namespace App\Filament\Resources\Inquiries\Tables;

use App\Models\Inquiry;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InquiriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Eingang')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Von')
                    ->searchable()
                    ->description(fn (Inquiry $record) => $record->email),

                TextColumn::make('subject')
                    ->label('Betreff')
                    ->searchable()
                    ->wrap()
                    ->limit(60)
                    ->placeholder('–'),

                TextColumn::make('type')
                    ->label('Herkunft')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state) => Inquiry::HERKUNFT[$state] ?? $state)
                    ->toggleable(),

                TextColumn::make('ticket_ref')
                    ->label('Ticket')
                    ->url(fn (Inquiry $record) => $record->ticket_url, shouldOpenInNewTab: true)
                    ->color('primary')
                    ->placeholder('–'),

                TextColumn::make('status')
                    ->label('Stand')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Inquiry::STATUS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'neu' => 'warning',
                        'in_arbeit' => 'info',
                        default => 'success',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Stand')
                    ->options(Inquiry::STATUS),

                SelectFilter::make('type')
                    ->label('Herkunft')
                    ->options(Inquiry::HERKUNFT),
            ])
            ->recordActions([
                EditAction::make()->label('Ansehen'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
