<?php

namespace App\Support;

/**
 * Was beim Übertragen zwischen lokal und Server mitgeht – und was nicht.
 *
 * Die Reihenfolge ist die der Fremdschlüssel: Eltern vor Kindern. Beim
 * Einlesen wird sie umgekehrt durchlaufen, damit nichts an einer Beziehung
 * hängen bleibt, deren Gegenstück schon weg ist.
 */
class Inhalt
{
    /** @var list<string> */
    public const TABELLEN = [
        'categories',
        'posts',
        'post_links',
        'products',
        'projects',
        'post_project',
        'service_categories',
        'services',
        'reviews',
        'team_members',
    ];

    /**
     * Tabellen, die bewusst draußen bleiben.
     *
     * users steht ganz oben und aus einem harten Grund: lokal liegt dort der
     * Vorgabewert aus DatabaseSeeder (info@nils-digital.de / umbau-lokal).
     * Ginge die Tabelle mit, schöbe der erste Transfer ein bekanntes Passwort
     * auf einen öffentlich erreichbaren Server. Zugänge werden auf dem Ziel
     * angelegt, nie übertragen.
     *
     * Der Rest ist Laufzeit: Sitzungen, Zwischenspeicher, Warteschlangen und
     * der Migrationsstand gehören der jeweiligen Maschine.
     */
    public const NICHT_UEBERTRAGEN = [
        'users',
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'migrations',
    ];
}
