<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 * Ein Teammitglied kann auf seine eigene Seite verweisen.
 *
 * Anlass ist Kevin: er hat unter kevins-werkstatt.nils-digital.de einen
 * eigenen Auftritt, der auf einem anderen Server läuft und von hier aus
 * bisher nirgends erreichbar war.
 *
 * Adresse und Beschriftung sind zwei Spalten, weil „Kevins Werkstatt" kein
 * Text ist, der sich aus der Adresse ableiten ließe – und weil er wie alles
 * Sichtbare in die Redaktion gehört, nicht ins Blade.
 *
 * Kevins Verweis trägt die Migration gleich ein, wie schon die Personen
 * selbst: auf dem Server läuft ausschließlich migrate.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->string('website_url')->nullable()->after('highlight_text');
            $table->string('website_label')->nullable()->after('website_url');
        });

        DB::table('team_members')
            ->where('name', 'Kevin')
            ->update([
                'website_url' => 'https://kevins-werkstatt.nils-digital.de',
                'website_label' => 'Kevins Werkstatt',
            ]);
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn(['website_url', 'website_label']);
        });
    }
};
