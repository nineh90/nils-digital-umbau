<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    /**
     * Der Weg einer Anfrage. Die Schlüssel stehen in der Datenbank, die
     * Beschriftungen in der Redaktion – eine Liste, damit beides nicht
     * auseinanderläuft.
     */
    public const STATUS = [
        'neu' => 'Neu',
        'in_arbeit' => 'In Arbeit',
        'erledigt' => 'Erledigt',
    ];

    public const HERKUNFT = [
        'kontakt' => 'Kontaktformular',
        'projektanfrage' => 'Projektfragebogen',
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public function scopeNeu(Builder $query): Builder
    {
        return $query->where('status', 'neu');
    }
}
