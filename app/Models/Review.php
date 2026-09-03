<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true)->orderBy('position');
    }

    /*
     * Stimmen, die sich als Zitat zeigen lassen.
     *
     * Eine Bewertung ohne Text – bei Google völlig üblich, man vergibt nur
     * Sterne – ergäbe eine leere Kachel mit fünf Sternen darüber. Für den
     * Schnitt zählt sie trotzdem mit, deshalb filtert erst diese Ebene sie
     * heraus und nicht schon scopeVisible.
     *
     * reorder() räumt die Sortierung nach position weg: sie stünde sonst vor
     * einem inRandomOrder() und die Auswahl wäre gar nicht zufällig.
     */
    public function scopeVorzeigbar(Builder $query): Builder
    {
        return $query->visible()->reorder()->whereNotNull('text')->where('text', '!=', '');
    }
}
