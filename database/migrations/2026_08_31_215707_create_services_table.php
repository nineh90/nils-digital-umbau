<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Einzelne Leistungen mit Preis.
 *
 * unit trägt die drei Werte der alten services.json: eur-ab, eur-pro-monat,
 * eur-pro-stunde. Daraus entsteht die Preisangabe im Text ("ab 499 €") und
 * gleichzeitig das Offer-JSON-LD auf der Leistungsseite.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('price')->nullable();
            $table->string('unit')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['service_category_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
