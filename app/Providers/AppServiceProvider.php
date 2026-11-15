<?php

namespace App\Providers;

use Filament\Forms\Components\FileUpload;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
         * Bilder werden im Browser verkleinert, bevor sie hochgeladen werden.
         *
         * Anlass war ein Handyfoto, das sich im Team-Formular nicht hochladen
         * liess: PHP steht in der Vorgabe auf 2 MB upload_max_filesize und
         * verwirft alles Groessere, bevor Laravel ueberhaupt etwas davon
         * mitbekommt - im Log steht deshalb nichts, in der Oberflaeche nur ein
         * Fehler ohne Grund. Auf dem Server sind es 12 MB (deploy/php.ini),
         * lokal die Vorgabe.
         *
         * Statt an jeder Maschine die php.ini zu drehen, verkleinert FilePond
         * das Bild vor dem Absenden. Aus zwoelf Megapixeln werden ein paar
         * hundert Kilobyte, die Grenze spielt keine Rolle mehr - und die Seite
         * laedt nebenbei schneller, weil niemand mehr aus Versehen ein
         * Originalfoto ausliefert.
         *
         * contain statt cover: der Bildausschnitt bleibt unangetastet, nur die
         * laengere Kante wird auf 1600 Pixel begrenzt. Zuschneiden ist eine
         * gestalterische Entscheidung und gehoert nicht in einen Uploader.
         *
         * configureUsing statt fuenfmal dasselbe an fuenf Feldern: das gilt
         * damit auch fuer jedes Bildfeld, das spaeter dazukommt.
         */
        FileUpload::configureUsing(function (FileUpload $feld): void {
            $feld->imageResizeMode('contain')
                ->imageResizeTargetWidth('1600')
                ->imageResizeTargetHeight('1600')
                ->imageResizeUpscale(false)
                ->maxSize(4096);
        });

        /*
         * Livewire nimmt Uploads zuerst in einen Zwischenspeicher und prüft
         * dabei vorab auf 12 MB. Videos im Beitragstext (NID-49) sind größer.
         * Die feldgenaue Grenze setzt danach das Feld selbst – für Bilder in
         * den Bildfeldern bleibt es bei den 4 MB oben.
         */
        config(['livewire.temporary_file_upload.rules' => [
            'required', 'file', 'max:'.\App\Filament\Resources\Posts\Schemas\PostForm::MEDIEN_MAX_KB,
        ]]);
    }
}
