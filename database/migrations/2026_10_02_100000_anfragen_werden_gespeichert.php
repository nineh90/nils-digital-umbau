<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Anfragen bleiben liegen, auch wenn die Mail nicht ankommt.
 *
 * Bisher wurde eine Kontaktanfrage ausschließlich verschickt. Scheiterte der
 * Versand – falsches Passwort, Google lehnt ab, die Warteschlange steht –,
 * war sie weg, und das Formular hatte trotzdem „Danke" gesagt. Die Mail ist
 * ab jetzt die Benachrichtigung, nicht mehr der einzige Ort, an dem die
 * Anfrage existiert.
 *
 * type und details sind für den eigenen Projektfragebogen vorgesehen, der
 * die Google-Forms-Einbettung ablösen soll: dieselbe Tabelle, eine zweite
 * Herkunft, die Antworten als JSON. Deshalb darf subject fehlen – ein
 * Fragebogen hat keinen Betreff.
 *
 * Bewusst keine IP-Adresse. Sie wird für die Drosselung im
 * KontaktController gebraucht, aber dort nur für eine Stunde im
 * Zwischenspeicher; hier stünde sie auf Dauer neben Name und Nachricht.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->default('kontakt');
            $table->string('name', 120);
            $table->string('email', 180);
            $table->string('subject', 180)->nullable();
            $table->text('message');
            $table->json('details')->nullable();
            $table->string('status', 20)->default('neu')->index();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
