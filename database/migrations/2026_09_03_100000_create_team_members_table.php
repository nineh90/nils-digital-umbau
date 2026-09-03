<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->string('image')->nullable();
            $table->text('bio');

            // Die Schlagwortleiste unter dem Text. Wie projects.tags eine
            // Liste, keine Beziehung – sie wird nirgends gefiltert oder
            // verknuepft, eine eigene Tabelle brauchte nur Joins.
            $table->json('skills')->nullable();

            // Der hervorgehobene Satz am Ende der Karte. Zwei Felder statt
            // einem, weil die Beschriftung fett vor dem Gedankenstrich steht
            // und pro Person anders lautet – "Arbeitsweise" bei Nils,
            // "Staerke" bei Kevin.
            $table->string('highlight_label')->nullable();
            $table->text('highlight_text')->nullable();

            $table->boolean('is_visible')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        /*
         * Die beiden bestehenden Personen gleich mitnehmen.
         *
         * Bewusst hier und nicht im Seeder: entrypoint.sh laesst auf dem
         * Server ausschliesslich migrate laufen, kein db:seed. Stuenden die
         * Daten im Seeder, waere die Teamseite auf der Vorschau nach diesem
         * Deploy leer. Es ist ein einmaliger Umzug von hart im Blade nach
         * Datenbank, keine wiederkehrende Befuellung.
         */
        $jetzt = now();

        DB::table('team_members')->insert([
            [
                'name' => 'Nils Nehring',
                'role' => 'Gründer & Lead-Entwickler',
                'image' => 'assets/images/sunny-nils.jpg',
                'bio' => 'Nils ist Gründer von Nils-Digital und dein direkter Ansprechpartner für Konzept, Umsetzung und Kommunikation. Er entwickelt Webseiten, Apps und digitale Lösungen, die nicht nur gut aussehen, sondern echte Ergebnisse liefern – von der ersten Idee bis zum Launch.',
                'skills' => json_encode(['Webdesign', 'Frontend', 'Backend', 'KI-Automatisierung', 'SEO', 'Projektleitung']),
                'highlight_label' => 'Arbeitsweise',
                'highlight_text' => 'Direkte Kommunikation, kurze Wege, transparente Absprachen. Wer dir antwortet, hat auch gebaut, worum es geht.',
                'is_visible' => true,
                'position' => 1,
                'created_at' => $jetzt,
                'updated_at' => $jetzt,
            ],
            [
                'name' => 'Kevin',
                'role' => 'Entwickler',
                'image' => null,
                'bio' => 'Kevin sorgt dafür, dass alles technisch sauber, stabil und zuverlässig läuft – besonders wenn Projekte komplex werden. Mit seinem Blick fürs Detail und strukturiertem Code liefert er genau die technische Tiefe, die anspruchsvolle Projekte brauchen.',
                'skills' => json_encode(['JavaScript', 'Frontend', 'Backend', 'Clean Code', 'Problemlösung']),
                'highlight_label' => 'Stärke',
                'highlight_text' => 'Kevin hat in kürzester Zeit über 70 Tickets umgesetzt – präzise, strukturiert und schneller als erwartet.',
                'is_visible' => true,
                'position' => 2,
                'created_at' => $jetzt,
                'updated_at' => $jetzt,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
