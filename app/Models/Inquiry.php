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
            'handed_over_at' => 'datetime',
        ];
    }

    /** Ob es ein Ticketsystem gibt, an das übergeben werden kann. */
    public static function uebergabeEingerichtet(): bool
    {
        return filled(config('services.ticketsystem.url')) && filled(config('services.ticketsystem.token'));
    }

    public function scopeNeu(Builder $query): Builder
    {
        return $query->where('status', 'neu');
    }
}
