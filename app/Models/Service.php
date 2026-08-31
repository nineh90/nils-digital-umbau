<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    /**
     * Preisangabe im Fließtext, z. B. "ab 499 €" oder "89 € / Stunde".
     * Die drei unit-Werte stammen unverändert aus der alten services.json.
     */
    public function priceLabel(): ?string
    {
        if ($this->price === null) {
            return null;
        }

        $betrag = number_format((float) $this->price, 0, ',', '.').' €';

        return match ($this->unit) {
            'eur-ab' => "ab {$betrag}",
            'eur-pro-monat' => "{$betrag} / Monat",
            'eur-pro-stunde' => "{$betrag} / Stunde",
            default => $betrag,
        };
    }
}
