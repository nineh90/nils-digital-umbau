<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditInquiry extends EditRecord
{
    protected static string $resource = InquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /*
             * Öffnet das eigene Mailprogramm. Geantwortet wird bewusst von
             * dort und nicht aus der Redaktion heraus: die Antwort soll im
             * Postfach liegen, wo auch der weitere Verlauf steht.
             */
            Action::make('antworten')
                ->label('Per Mail antworten')
                ->icon(Heroicon::OutlinedEnvelope)
                ->url(fn () => 'mailto:'.$this->record->email
                    .'?subject='.rawurlencode('Re: '.($this->record->subject ?? 'Deine Anfrage'))),

            DeleteAction::make(),
        ];
    }
}
