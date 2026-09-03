<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Wer im Schema.org-Block als Mitarbeiter auftaucht – und wer nicht.
 *
 * Anlass ist Sunny. Sie gehört auf die Teamseite, aber nicht in die
 * strukturierten Daten: dort stünde sie als Person mit einem jobTitle, und
 * Google nimmt so etwas wörtlich. Falsche Auszeichnung ist schlechter als
 * gar keine.
 *
 * Bewusst eine Spalte und keine Ausnahme im Blade: die Entscheidung gehört
 * zum Datensatz, nicht in eine Vorlage, in der später jemand einen Namen
 * abfragt.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->boolean('in_schema')->default(true)->after('is_visible');
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn('in_schema');
        });
    }
};
