<?php

namespace App\Filament\Resources\Notices\Pages;

use App\Filament\Resources\Notices\NoticeResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditNotice extends EditRecord
{
    protected static string $resource = NoticeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /*
             * Speichert vorher, sonst zeigt die Vorschau den Stand von vor der
             * Bearbeitung. Die Route liegt hinter der Anmeldung und zeigt den
             * Hinweis auch dann, wenn er noch ausgeschaltet ist.
             */
            Action::make('vorschau')
                ->label('Vorschau')
                ->icon(Heroicon::OutlinedEye)
                ->color('gray')
                ->action(function () {
                    $this->save(shouldRedirect: false);

                    $this->js(
                        'window.open('.json_encode(route('hinweis.vorschau', $this->record)).", '_blank')"
                    );
                }),

            DeleteAction::make(),
        ];
    }
}
