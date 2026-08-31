<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Schaltflächen am Ende eines Beitrags.
 *
 * Eigene Tabelle, weil der alte Mini-Markdown-Parser keine Link-Syntax kannte
 * und Links deshalb separat gepflegt werden mussten. Mit CommonMark wären Links
 * im Fließtext möglich – die Buttons am Textende bleiben trotzdem sinnvoll.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->string('label');
            $table->unsignedSmallInteger('position')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_links');
    }
};
