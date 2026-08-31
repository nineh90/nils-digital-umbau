<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Produktangaben für Shop-Beiträge (14 der 46 Altbeiträge).
 *
 * Erzeugt die Produktbox und das Product-JSON-LD. Ist ein Produkt gesetzt,
 * unterdrückte der alte Renderer das Hero-Bild und die Link-Buttons – dieses
 * Verhalten bleibt.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('image')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->string('availability')->nullable();
            $table->text('shop_url')->nullable();
            $table->string('type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
