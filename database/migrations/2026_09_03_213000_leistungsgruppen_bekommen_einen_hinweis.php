<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Ein Satz unter der Gruppenüberschrift.
 *
 * Anlass sind die Erweiterungen: Shop und Blog werden zu einem Paket
 * dazugebucht, ihre Kosten kommen oben drauf. Auf der Seite stand bisher nur
 * "39 € / Monat" – das liest sich wie ein eigenständiges Angebot, und genau
 * dieser Irrtum landet später im Angebot und in der Rechnung.
 *
 * Als Spalte an der Gruppe und nicht als Absatz im Blade, weil sich die
 * Gruppen in der Redaktion anlegen lassen: eine neue Gruppe soll ihren
 * Hinweis mitbringen können, ohne dass jemand eine Vorlage anfasst.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_categories', function (Blueprint $table) {
            $table->string('note')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('service_categories', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};
