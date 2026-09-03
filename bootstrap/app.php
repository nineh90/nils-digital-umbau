<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /*
         * Traefik beendet TLS und reicht die Anfrage per HTTP weiter.
         * Ohne diese Zeile hält Laravel jede Anfrage für unverschlüsselt und
         * baut alle Adressen mit http:// – Canonical, og:url, Sitemap und
         * jede 301. Für Google wäre das eine zweite, unverschlüsselte Fassung
         * der Seite.
         *
         * "*" ist hier richtig: der Container ist ausschließlich über Traefik
         * erreichbar, es gibt keinen Weg an dem Proxy vorbei.
         */
        $middleware->trustProxies(at: '*');

        /*
         * Wohin ein nicht Angemeldeter geschickt wird.
         *
         * Laravels Vorgabe zeigt auf eine Route namens "login", die es hier
         * nicht gibt – die Anmeldung gehört Filament. Ohne diese Zeile endet
         * jeder Aufruf einer geschützten Adresse (etwa der Beitragsvorschau)
         * in einem 500er statt auf der Anmeldeseite.
         */
        $middleware->redirectGuestsTo(fn () => route('filament.admin.auth.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
