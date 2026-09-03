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

        return match ($this->unit) {
            'eur-ab' => 'ab '.$this->euro($this->price),
            'eur-pro-monat' => $this->euro($this->price).' / Monat',
            'eur-pro-stunde' => $this->euro($this->price).' / Stunde',
            default => $this->euro($this->price),
        };
    }

    /**
     * Hat die Leistung ein Abo? Nur der Monatspreis entscheidet – Laufzeit und
     * Anschlusspreis sind Beiwerk, das fehlen darf.
     */
    public function hatAbo(): bool
    {
        return $this->monthly_price !== null;
    }

    /** Monatspreis des Abos, z. B. "99 € / Monat". */
    public function aboLabel(): ?string
    {
        return $this->hatAbo() ? $this->euro($this->monthly_price).' / Monat' : null;
    }

    /**
     * Was in der monatlichen Ansicht steht – auch für Leistungen ohne eigenes Abo.
     *
     * Hosting und Pflege stecken im Monatspreis; sie dort noch einmal mit
     * eigenem Betrag zu zeigen, liesse den Preis doppelt aussehen. Stundensätze
     * gelten dagegen in beiden Modellen unverändert: Arbeit über den
     * enthaltenen Umfang hinaus wird so oder so nach Aufwand abgerechnet.
     */
    public function aboAnsicht(): string
    {
        if ($this->hatAbo()) {
            return (string) $this->aboLabel();
        }

        return match ($this->unit) {
            'eur-pro-monat' => 'im Abo enthalten',
            default => $this->priceLabel() ?? 'auf Anfrage',
        };
    }

    /**
     * Die Bedingungen des Abos in einem Satz: Laufzeit, was danach gilt und
     * eine etwaige Anzahlung.
     *
     * Der Satz steht bewusst hier und nicht im Blade: Was ein Kunde vor dem
     * Abschluss lesen muss, soll sich nicht je nach Seite anders lesen.
     */
    public function aboBedingungen(): ?string
    {
        if (! $this->hatAbo()) {
            return null;
        }

        $teile = [];

        // Die Einrichtung zuerst: das ist der Betrag, der sofort fällig wird.
        // Hinten angestellt läse sich der Satz, als käme er noch obendrauf,
        // nachdem der Kunde die Zahl im Kopf schon abgehakt hat.
        if ($this->setup_fee) {
            $teile[] = $this->euro($this->setup_fee).' Einrichtung einmalig';
        }

        if ($this->term_months) {
            $teile[] = "{$this->term_months} Monate Mindestlaufzeit";
        }

        if ($this->renewal_price !== null) {
            $teile[] = 'danach '.$this->euro($this->renewal_price).' / Monat, monatlich kündbar';
        }

        return $teile === [] ? null : ucfirst(implode(', ', $teile)).'.';
    }

    /**
     * Was das Abo über die Erstellung hinaus kostet – der Betrag, den ein
     * Kunde beim Rechnen sucht.
     */
    public function aboGesamt(): ?int
    {
        if (! $this->hatAbo() || ! $this->term_months) {
            return null;
        }

        return $this->monthly_price * $this->term_months + (int) $this->setup_fee;
    }

    private function euro(int|float $betrag): string
    {
        return number_format((float) $betrag, 0, ',', '.').' €';
    }
}
