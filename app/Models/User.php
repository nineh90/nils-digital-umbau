<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Zugang zur Redaktion.
     *
     * Ohne diese Methode sperrt Filament ausserhalb der lokalen Umgebung jeden
     * aus – auf dem Server koennte sich also niemand anmelden. Konten werden
     * ausschliesslich von Hand angelegt, es gibt keine oeffentliche
     * Registrierung. Wer in der Tabelle steht, gehoert zum Team und darf rein.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
