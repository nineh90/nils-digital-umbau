<?php

namespace App\Models;

use App\Support\Markdown;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_internal' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    /** Karten für das Carousel auf der Startseite. */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true)->orderBy('position');
    }

    public function bodyHtml(): string
    {
        return app(Markdown::class)->toHtml($this->body);
    }

    /** Nur Projekte mit Fallstudie bekommen eine eigene Detailseite. */
    public function hasCaseStudy(): bool
    {
        return filled($this->body);
    }
}
