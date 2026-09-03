<?php

namespace App\Filament\Resources\Notices\Pages;

use App\Filament\Resources\Notices\NoticeResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;

class CreateNotice extends CreateRecord
{
    protected static string $resource = NoticeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /*
             * Beim Anlegen legt die Vorschau den Hinweis wirklich an - anders
             * geht es nicht, die Vorschau braucht einen Datensatz. Er ist dabei
             * ausgeschaltet, es sei denn, jemand hat den Schalter schon
             * umgelegt. Danach steht man auf der Bearbeiten-Seite.
             */
            Action::make('vorschau')
                ->label('Vorschau')
                ->icon(Heroicon::OutlinedEye)
                ->color('gray')
                ->action(function () {
                    $this->create(another: false);

                    $this->js(
                        'window.open('.json_encode(route('hinweis.vorschau', $this->record)).", '_blank')"
                    );
                }),
        ];
    }
}
