<?php

namespace App\Filament\Resources\Inquiries;

use App\Filament\Resources\Inquiries\Pages\EditInquiry;
use App\Filament\Resources\Inquiries\Pages\ListInquiries;
use App\Filament\Resources\Inquiries\Schemas\InquiryForm;
use App\Filament\Resources\Inquiries\Tables\InquiriesTable;
use App\Models\Inquiry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * Was über die Formulare der Seite hereinkommt.
 *
 * Eine eigene Gruppe ganz oben und nicht unter „Inhalte": Inhalte pflegt man,
 * wenn man Zeit hat – eine Anfrage wartet auf Antwort.
 */
class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $modelLabel = 'Anfrage';

    protected static ?string $pluralModelLabel = 'Anfragen';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Eingang';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    public static function form(Schema $schema): Schema
    {
        return InquiryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InquiriesTable::configure($table);
    }

    /** Anfragen entstehen auf der Seite, nicht in der Redaktion. */
    public static function canCreate(): bool
    {
        return false;
    }

    /** Die Zahl der unbeantworteten – steht sie da, ist etwas zu tun. */
    public static function getNavigationBadge(): ?string
    {
        $neu = Inquiry::neu()->count();

        return $neu > 0 ? (string) $neu : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInquiries::route('/'),
            'edit' => EditInquiry::route('/{record}/edit'),
        ];
    }
}
