<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /*
             * Keine eigene Vorschau-Route wie bei Beiträgen: Projektseiten
             * kennen keinen Entwurf, sie sind immer öffentlich. Gespeichert
             * wird trotzdem vorher, sonst zeigte die Seite den alten Stand.
             */
            Action::make('ansehen')
                ->label('Ansehen')
                ->icon(Heroicon::OutlinedEye)
                ->color('gray')
                ->action(function () {
                    $this->save(shouldRedirect: false);

                    $this->js(
                        'window.open('.json_encode(route('projekte.show', $this->record)).", '_blank')"
                    );
                }),

            DeleteAction::make(),
        ];
    }
}
