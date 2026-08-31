<?php

namespace App\Models;

use App\Support\Markdown;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(PostLink::class)->orderBy('position');
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function contentHtml(): string
    {
        return app(Markdown::class)->toHtml($this->content);
    }

    public function readingMinutes(): int
    {
        return app(Markdown::class)->readingMinutes($this->content ?? '', $this->teaser ?? '');
    }

    /**
     * Shop-Beiträge zeigten schon bisher statt des Hero-Bildes die Produktbox
     * und unterdrückten die Link-Buttons. Das Verhalten bleibt.
     */
    public function isProduct(): bool
    {
        return $this->product !== null;
    }
}
