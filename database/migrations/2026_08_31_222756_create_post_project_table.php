<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Verbindung zwischen Beiträgen und Projekten.
 *
 * Auf der alten Seite standen beide Welten unverbunden nebeneinander: Es gab
 * neun Beiträge über Lerndex und eine Projektkarte zu Lerndex, aber keinen Weg
 * von der einen zur anderen. Für Besucher eine Sackgasse, für Google eine
 * verschenkte interne Verlinkung.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_project', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_project');
    }
};
