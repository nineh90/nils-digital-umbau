<?php

namespace App\Filament\Resources\ServiceCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Gruppe')
                ->required()
                ->maxLength(60)
                ->helperText('Überschrift eines Abschnitts auf der Leistungsseite.')
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, callable $set) => $operation === 'create'
                    ? $set('slug', Str::slug((string) $state, '-', 'de'))
                    : null),

            TextInput::make('slug')
                ->label('Kennung')
                ->required()
                ->unique(ignoreRecord: true)
                ->helperText('Dient als Sprungmarke auf der Leistungsseite.'),

            TextInput::make('note')
                ->label('Hinweis')
                ->maxLength(160)
                ->helperText('Ein Satz unter der Überschrift – für alles, was sonst am einzelnen Preis missverstanden wird, etwa dass eine Erweiterung zusätzlich zu einem Paket gebucht wird.'),

            TextInput::make('position')
                ->label('Reihenfolge')
                ->numeric()
                ->default(0)
                ->helperText('Kleinere Zahl steht weiter oben.'),
        ]);
    }
}
