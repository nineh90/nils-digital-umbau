<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Blogbeiträge.
 *
 * legacy_id ist Pflicht und darf nie neu vergeben werden: die alten Adressen
 * lauteten /pages/blog-post.html?id=N. Ohne dieses Feld lassen sich die
 * 301-Weiterleitungen nicht auflösen und die Beiträge verlieren ihre Rankings.
 *
 * hero_image statt einer eigenen Bildtabelle: in allen 46 Altbeiträgen enthält
 * images[] genau ein Element. Der alte Renderer konnte zwar mehr (ab images[1]
 * im Fließtext), genutzt wurde es nie.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('legacy_id')->nullable()->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('teaser');
            $table->text('content');
            $table->string('hero_image')->nullable();
            $table->string('thumb_fit')->nullable();
            $table->string('status')->default('published');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
