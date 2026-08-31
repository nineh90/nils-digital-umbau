<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('hero_image')
                    ->label('')
                    ->disk('inhalte')
                    ->height(40)
                    ->width(64),

                TextColumn::make('title')
                    ->label('Titel')
                    ->searchable()
                    ->wrap()
                    ->limit(70)
                    ->description(fn ($record) => '/blog/'.$record->slug),

                TextColumn::make('category.name')
                    ->label('Kategorie')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'published' ? 'Veröffentlicht' : 'Entwurf')
                    ->color(fn (string $state) => $state === 'published' ? 'success' : 'gray'),

                TextColumn::make('published_at')
                    ->label('Datum')
                    ->date('d.m.Y')
                    ->sortable(),

                // Nützlich beim Aufräumen: die alte ID ist der Anker für die
                // 301-Weiterleitungen von /pages/blog-post.html?id=N.
                TextColumn::make('legacy_id')
                    ->label('Alte ID')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategorie')
                    ->relationship('category', 'name')
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'published' => 'Veröffentlicht',
                        'draft' => 'Entwurf',
                    ]),

                TernaryFilter::make('hero_image')
                    ->label('Beitragsbild')
                    ->placeholder('Alle')
                    ->trueLabel('Mit Bild')
                    ->falseLabel('Ohne Bild')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('hero_image'),
                        false: fn ($query) => $query->whereNull('hero_image'),
                        blank: fn ($query) => $query,
                    ),
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
