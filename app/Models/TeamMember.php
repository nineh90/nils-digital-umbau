<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'skills' => 'array',
            'is_visible' => 'boolean',
        ];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true)->orderBy('position');
    }

    /** Anfangsbuchstabe für das Monogramm, wenn kein Foto hinterlegt ist. */
    public function monogramm(): string
    {
        return mb_strtoupper(mb_substr($this->name, 0, 1));
    }
}
