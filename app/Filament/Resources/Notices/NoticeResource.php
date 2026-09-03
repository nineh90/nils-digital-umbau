<?php

namespace App\Filament\Resources\Notices;

use App\Filament\Resources\Notices\Pages\CreateNotice;
use App\Filament\Resources\Notices\Pages\EditNotice;
use App\Filament\Resources\Notices\Pages\ListNotices;
use App\Filament\Resources\Notices\Schemas\NoticeForm;
use App\Filament\Resources\Notices\Tables\NoticesTable;
use App\Models\Notice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NoticeResource extends Resource
{
    protected static ?string $model = Notice::class;

    protected static ?string $modelLabel = 'Hinweis';

    protected static ?string $pluralModelLabel = 'Hinweise';

    protected static string|\UnitEnum|null $navigationGroup = 'Inhalte';

    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    public static function form(Schema $schema): Schema
    {
        return NoticeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NoticesTable::configure($table);
    }

    /** Zeigt in der Navigation, ob gerade ein Hinweis über der Seite liegt. */
    public static function getNavigationBadge(): ?string
    {
        $anzahl = Notice::aktiv()->count();

        return $anzahl > 0 ? (string) $anzahl : null;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNotices::route('/'),
            'create' => CreateNotice::route('/create'),
            'edit' => EditNotice::route('/{record}/edit'),
        ];
    }
}
