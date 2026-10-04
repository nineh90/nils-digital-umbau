<?php

namespace App\Filament\Resources\Inquiries\Pages;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Jobs\AnfrageUebergeben;
use App\Models\Inquiry;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Throwable;

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

            Action::make('ticket')
                ->label(fn () => 'Ticket '.$this->record->ticket_ref)
                ->icon(Heroicon::OutlinedTicket)
                ->color('gray')
                ->url(fn () => $this->record->ticket_url, shouldOpenInNewTab: true)
                ->visible(fn () => filled($this->record->ticket_url)),

            /*
             * Für den Fall, dass die Übergabe dreimal gescheitert ist, weil
             * das Ticketsystem nicht erreichbar war. Sofort und nicht über
             * die Warteschlange: wer hier klickt, will sehen, ob es klappt.
             */
            Action::make('uebergeben')
                ->label('Ans Ticketsystem übergeben')
                ->icon(Heroicon::OutlinedArrowUpOnSquare)
                ->color('gray')
                ->visible(fn () => blank($this->record->ticket_url) && Inquiry::uebergabeEingerichtet())
                ->action(function () {
                    try {
                        AnfrageUebergeben::dispatchSync($this->record);
                    } catch (Throwable $fehler) {
                        report($fehler);

                        Notification::make()
                            ->title('Das Ticketsystem hat nicht geantwortet')
                            ->body('Die Anfrage bleibt hier liegen. Versuch es später noch einmal.')
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Ticket '.$this->record->refresh()->ticket_ref.' angelegt')
                        ->success()
                        ->send();
                }),

            DeleteAction::make(),
        ];
    }
}
