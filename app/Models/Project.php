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

    /**
     * Stand für Projekte, die wir gebaut haben, die aber nicht mehr unsere
     * sind: die Seite ist abgeschaltet, oder die Zusammenarbeit ist beendet.
     * Die Arbeit bleibt als Referenz stehen, nur verlinkt sie nicht mehr nach
     * draußen – was dort heute läuft, haben wir nicht mehr in der Hand.
     */
    public const FRUEHER = 'archived';

    /**
     * Karten für die Startseite.
     *
     * Frühere Projekte nie, auch wenn der Schalter noch an ist: der Hero
     * verspricht eine Seite, die man besuchen kann, und die drei Kacheln
     * darunter sollen zeigen, woran wir gerade arbeiten.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->aktuell()->where('is_featured', true)->orderBy('position');
    }

    /** Alles außer früheren Projekten. Ein leerer Stand zählt als aktuell. */
    public function scopeAktuell(Builder $query): Builder
    {
        return $query->where(fn (Builder $q) => $q
            ->whereNull('status')
            ->orWhere('status', '!=', self::FRUEHER));
    }

    public function scopeFrueher(Builder $query): Builder
    {
        return $query->where('status', self::FRUEHER);
    }

    public function istFrueher(): bool
    {
        return $this->status === self::FRUEHER;
    }

    /**
     * Der Link nach draußen, sofern er gezeigt werden soll. Die Adresse bleibt
     * bei früheren Projekten gespeichert – als Gedächtnis für die Redaktion –,
     * die Seite nennt sie aber nicht mehr.
     */
    public function sichtbarerLink(): ?string
    {
        return $this->istFrueher() ? null : $this->link;
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
