<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Referenzen und Projekte.
 *
 * body ist neu und der eigentliche Gewinn: bisher gab es nur Karten mit einem
 * Link nach draußen, also sieben Referenzen ohne eine einzige indexierbare
 * Detailseite. Hier steht künftig die Fallstudie.
 *
 * is_featured ersetzt die hart in index.html kopierten .home-proj-card-Karten.
 * Bisher musste jedes Projekt an zwei Stellen gepflegt werden – Startseite und
 * Projektseite liefen dadurch zwangsläufig auseinander.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->text('description');
            $table->text('body')->nullable();
            $table->string('image')->nullable();
            $table->string('image_fit')->default('contain');
            $table->string('link')->nullable();
            $table->boolean('is_internal')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->json('tags')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
