<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /*
             * Vorschau im neuen Tab.
             *
             * Speichert vorher, sonst zeigt die Vorschau den Stand vor der
             * Bearbeitung – und das fällt niemandem auf, weil die Seite ja
             * aufgeht. Die Route liegt hinter der Anmeldung und zeigt deshalb
             * auch Entwürfe.
             */
            Action::make('vorschau')
                ->label('Vorschau')
                ->icon(Heroicon::OutlinedEye)
                ->color('gray')
                ->action(function () {
                    $this->save(shouldRedirect: false);

                    $this->js(
                        'window.open('.json_encode(route('blog.vorschau', $this->record)).", '_blank')"
                    );
                }),

            DeleteAction::make(),
        ];
    }
}
