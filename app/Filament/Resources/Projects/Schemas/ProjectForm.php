<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('type'),
                TextInput::make('status'),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('body')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->image(),
                FileUpload::make('image_fit')
                    ->image()
                    ->required(),
                TextInput::make('link'),
                Toggle::make('is_internal')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
                Textarea::make('tags')
                    ->columnSpanFull(),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
