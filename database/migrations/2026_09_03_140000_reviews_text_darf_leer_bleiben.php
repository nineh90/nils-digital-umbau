<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Eine Bewertung ohne Text ist der Normalfall, kein Sonderfall: bei Google
 * vergeben die meisten nur Sterne. Die Anzeige trägt dem längst Rechnung –
 * Review::vorzeigbar() filtert textlose Stimmen aus den Zitaten heraus und
 * zählt sie trotzdem für den Schnitt.
 *
 * Nur die Spalte wusste nichts davon: sie stand auf NOT NULL, und das
 * Formular verlangte den Text entsprechend. Eine reine Sternebewertung liess
 * sich damit gar nicht anlegen.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('text')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Zurueck geht nur, was keinen leeren Text hat – sonst bricht die
        // Spalte an genau den Daten, für die sie geöffnet wurde.
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('text')->nullable(false)->change();
        });
    }
};
