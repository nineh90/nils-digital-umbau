<?php

namespace App\Filament\Resources\Notices\Tables;

use App\Models\Notice;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NoticesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('position')
            ->reorderable('position')
            ->columns([
                TextColumn::make('title')
                    ->label('Hinweis')
                    ->description(fn (Notice $r) => str($r->body)->limit(60))
                    ->searchable(),

                TextColumn::make('scheme')
                    ->label('Aussehen')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => str(Notice::schemata()[$state] ?? $state)->before(' –'))
                    ->color('gray'),

                /*
                 * Die Spalte, auf die es ankommt: "eingeschaltet" allein sagt
                 * nichts, solange ein Zeitraum daran hängt. Hier steht, ob der
                 * Hinweis jetzt gerade zu sehen ist.
                 */
                IconColumn::make('sichtbar')
                    ->label('Läuft gerade')
                    ->state(fn (Notice $r) => Notice::aktiv()->whereKey($r->id)->exists())
                    ->boolean(),

                TextColumn::make('starts_at')
                    ->label('Zeitraum')
                    ->state(fn (Notice $r) => match (true) {
                        $r->starts_at && $r->ends_at => $r->starts_at->format('d.m.y').' – '.$r->ends_at->format('d.m.y'),
                        (bool) $r->ends_at => 'bis '.$r->ends_at->format('d.m.y'),
                        (bool) $r->starts_at => 'ab '.$r->starts_at->format('d.m.y'),
                        default => 'unbefristet',
                    }),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
