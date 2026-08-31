{{--
    Übergangsseite für die Bauphase.

    Steht auf "/", bis die echte Startseite gebaut ist. Die Farbwerte sind die
    Design-Tokens aus dem :root der alten legacy/css/main.css – damit die Marke
    von Anfang an stimmt und wir sie nicht zweimal festlegen.
--}}
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Nils-Digital – Umbau</title>
    <style>
        :root {
            --bg-main: #0d1117;
            --bg-card: #161b22;
            --accent: #00bcd4;
            --text-main: #e6edf3;
            --text-muted: #8b949e;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem;
            background: var(--bg-main);
            color: var(--text-main);
            font-family: ui-monospace, "Roboto Mono", SFMono-Regular, Menlo, monospace;
            line-height: 1.6;
        }
        .karte {
            width: min(42rem, 100%);
            background: var(--bg-card);
            border: 1px solid #21262d;
            border-radius: 14px;
            padding: 2.5rem;
        }
        h1 { margin: 0 0 .25rem; font-size: 1.6rem; letter-spacing: -.02em; }
        h1 span { color: var(--accent); }
        p.unter { margin: 0 0 2rem; color: var(--text-muted); }
        dl { display: grid; grid-template-columns: auto 1fr; gap: .4rem 1.5rem; margin: 0 0 2rem; font-size: .9rem; }
        dt { color: var(--text-muted); }
        dd { margin: 0; }
        a.knopf {
            display: inline-block;
            padding: .6rem 1.1rem;
            border: 1px solid var(--accent);
            border-radius: 8px;
            color: var(--accent);
            text-decoration: none;
            font-size: .9rem;
        }
        a.knopf:hover { background: var(--accent); color: var(--bg-main); }
        footer { margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid #21262d; color: var(--text-muted); font-size: .8rem; }
    </style>
</head>
<body>
    <main class="karte">
        <h1>Nils-<span>Digital</span></h1>
        <p class="unter">Gerüst steht. Hier entsteht die neue Startseite.</p>

        <dl>
            <dt>Laravel</dt><dd>{{ app()->version() }}</dd>
            <dt>PHP</dt><dd>{{ PHP_VERSION }}</dd>
            <dt>Umgebung</dt><dd>{{ app()->environment() }}</dd>
            <dt>Datenbank</dt><dd>{{ config('database.default') }}</dd>
        </dl>

        <a class="knopf" href="/admin">Zur Redaktion →</a>

        <footer>
            Die alte Seite liegt unverändert unter <code>legacy/</code> und dient als
            Quelle für den Import.
        </footer>
    </main>
</body>
</html>
