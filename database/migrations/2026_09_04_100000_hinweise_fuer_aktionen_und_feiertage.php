<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Hinweise, die beim Aufruf der Seite erscheinen – Aktionen, Feiertage,
 * Betriebsferien.
 *
 * Tabelle und Spalten englisch wie bei den übrigen Modellen; sichtbar wird
 * daraus die Komponente <x-hinweis>.
 *
 * scheme statt einzelner Farbspalten: die Redaktion wählt ein fertiges Schema,
 * die Farben kommen aus den Design-Tokens. Freie Farbwähler hätten bedeutet,
 * dass sich in der Redaktion etwas bauen lässt, das neben der Seite steht –
 * und Farben stehen in diesem Projekt aus gutem Grund nirgends hart im Markup.
 *
 * starts_at und ends_at dürfen beide leer sein: ein Hinweis ohne Zeitraum
 * läuft, bis jemand ihn abschaltet. Für "ab Nikolaus bis Neujahr" setzt man
 * beides und muss nicht daran denken, ihn wieder auszuschalten.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('image')->nullable();

            $table->string('placement')->default('center');   // center | top | corner
            $table->string('scheme')->default('akzent');
            $table->string('icon')->nullable();

            $table->string('button_label')->nullable();
            $table->string('button_url')->nullable();

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();

            // once = einmal je Besucher, session = einmal je Besuch,
            // always = bei jedem Seitenaufruf. Letzteres nur für Notfälle.
            $table->string('frequency')->default('once');

            $table->boolean('is_active')->default(false);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
