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

    /**
     * Die Links unter dem Beitrag, ohne die auf frühere Projekte.
     *
     * Ein Beitrag über ein Projekt trägt meist einen Knopf zur Seite des
     * Kunden. Wird das Projekt später zu einem früheren, soll dieser Knopf
     * verschwinden, ohne dass jemand daran denken muss, jeden alten Beitrag
     * einzeln anzufassen. Verglichen wird über den Host, weil Projekt und
     * Beitrag dieselbe Seite oft unterschiedlich schreiben (mit oder ohne
     * www, mit Pfad, mit Schrägstrich am Ende).
     *
     * @return \Illuminate\Support\Collection<int, PostLink>
     */
    public function sichtbareLinks()
    {
        $host = fn (?string $url) => preg_replace('/^www\./', '', strtolower((string) parse_url((string) $url, PHP_URL_HOST)));

        $gesperrt = $this->projects
            ->filter(fn (Project $p) => $p->istFrueher() && $p->link)
            ->map(fn (Project $p) => $host($p->link))
            ->filter()
            ->all();

        return $this->links->reject(fn (PostLink $l) => in_array($host($l->url), $gesperrt, true))->values();
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
