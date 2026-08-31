<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Blog-Kategorien.
 *
 * Löst die .cat-…-Klassen aus der alten main.css ab: die Badge-Farbe wurde dort
 * aus dem Kategorienamen erzeugt (lowercase, Leerzeichen zu Bindestrich), was
 * bei "Lernsoftware - Lerndex" zu der Klasse .cat-lernsoftware---lerndex führte.
 * Fehlte die Regel, fiel das Badge auf transparentes Schwarz zurück. Die Farbe
 * steht deshalb jetzt als Wert am Datensatz.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            // Volle CSS-Farbangabe statt Hex: die Badges der alten Seite
            // arbeiten mit Transparenz, z. B. rgba(255, 152, 0, 0.75).
            $table->string('color')->nullable();
            $table->string('text_color')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
