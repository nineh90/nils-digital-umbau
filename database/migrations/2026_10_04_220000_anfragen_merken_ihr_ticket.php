<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Eine Anfrage weiß, welches Ticket aus ihr geworden ist.
 *
 * Anfragen werden an das Ticketsystem auf intern.nils-digital.de übergeben
 * und dort bearbeitet. Hier bleibt der Beleg – und der braucht den Weg
 * hinüber: Kennung zum Lesen, Adresse zum Klicken, Zeitpunkt, um zu sehen,
 * ob die Übergabe überhaupt stattgefunden hat.
 *
 * Bewusst keine Fremd-ID als Zahl: die beiden Anwendungen teilen keine
 * Datenbank, und die Kennung (ANF-12) ist drüben das, was stabil bleibt.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('ticket_ref', 40)->nullable();
            $table->string('ticket_url')->nullable();
            $table->timestamp('handed_over_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['ticket_ref', 'ticket_url', 'handed_over_at']);
        });
    }
};
