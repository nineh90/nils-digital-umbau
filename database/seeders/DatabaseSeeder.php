<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Legt den Redaktionszugang an.
     *
     * Grund für den Seeder: "migrate:fresh" leert auch die Benutzertabelle. Wer
     * während der Bauphase das Schema nachzieht, steht danach ohne Zugang da –
     * und merkt es erst beim nächsten Anmeldeversuch. Mit "migrate:fresh --seed"
     * ist der Zugang sofort wieder da.
     *
     * Das Passwort steht bewusst NICHT im Code. Lokal gibt es einen offensichtlich
     * unbrauchbaren Vorgabewert für die SQLite-Datei auf dem eigenen Rechner;
     * produktiv muss ADMIN_PASSWORT gesetzt sein, sonst wird nichts angelegt.
     */
    public function run(): void
    {
        $passwort = env('ADMIN_PASSWORT');

        if (blank($passwort)) {
            if (app()->environment('production')) {
                $this->command->warn('ADMIN_PASSWORT nicht gesetzt – kein Zugang angelegt.');
                $this->command->warn('Auf dem Server stattdessen: php artisan make:filament-user');

                return;
            }

            $passwort = 'umbau-lokal';
        }

        $benutzer = User::updateOrCreate(
            ['email' => env('ADMIN_MAIL', 'info@nils-digital.de')],
            [
                'name' => env('ADMIN_NAME', 'Nils'),
                'password' => Hash::make($passwort),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Redaktionszugang: {$benutzer->email}");
    }
}
