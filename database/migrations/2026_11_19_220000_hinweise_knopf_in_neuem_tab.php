<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Ob der Knopf eines Hinweises in einem neuen Tab öffnet.
 *
 * Ein Schalter und keine Erkennung an der Adresse: Aktionen verweisen oft auf
 * fremde Seiten, dann soll die eigene offen bleiben – aber ob, entscheidet die
 * Redaktion, wie beim Projekt mit is_internal.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->boolean('button_new_tab')->default(false)->after('button_url');
        });
    }

    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn('button_new_tab');
        });
    }
};
