<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Response;

/**
 * Sitemap und robots.txt.
 *
 * Beide wurden bisher von Hand gepflegt – mit dem Ergebnis, dass in der alten
 * sitemap.xml elf Adressen standen und kein einziger der 46 Blogbeiträge.
 * Hier entstehen sie aus der Datenbank und können gar nicht mehr veralten.
 */
class SitemapController extends Controller
{
    public function index(): Response
    {
        $eintraege = collect();

        foreach ([
            ['start', 'weekly', '1.0'],
            ['leistungen', 'monthly', '0.9'],
            ['projekte', 'weekly', '0.8'],
            ['blog.index', 'weekly', '0.8'],
            ['ueber-uns', 'monthly', '0.7'],
            ['team', 'monthly', '0.6'],
            ['kontakt', 'yearly', '0.7'],
            ['projektanfrage', 'yearly', '0.6'],
            ['termine', 'yearly', '0.6'],
            ['impressum', 'yearly', '0.2'],
            ['datenschutz', 'yearly', '0.2'],
            ['agb', 'yearly', '0.2'],
        ] as [$route, $frequenz, $gewicht]) {
            $eintraege->push(['url' => route($route), 'datum' => null, 'frequenz' => $frequenz, 'gewicht' => $gewicht]);
        }

        foreach (Post::published()->latest('published_at')->get() as $beitrag) {
            $eintraege->push([
                'url' => route('blog.show', $beitrag),
                'datum' => $beitrag->updated_at,
                'frequenz' => 'monthly',
                'gewicht' => '0.7',
            ]);
        }

        foreach (Category::whereHas('posts', fn ($q) => $q->published())->get() as $kategorie) {
            $eintraege->push([
                'url' => route('blog.kategorie', $kategorie),
                'datum' => null,
                'frequenz' => 'weekly',
                'gewicht' => '0.5',
            ]);
        }

        // Nur Projekte mit Fallstudie. Ohne Text ist die Seite dünn und steht
        // deshalb auf noindex – dann gehört sie auch nicht in die Sitemap,
        // sonst widersprechen sich beide Signale.
        foreach (Project::whereNotNull('body')->where('body', '!=', '')->get() as $projekt) {
            $eintraege->push([
                'url' => route('projekte.show', $projekt),
                'datum' => $projekt->updated_at,
                'frequenz' => 'monthly',
                'gewicht' => '0.8',
            ]);
        }

        return response()
            ->view('sitemap', ['eintraege' => $eintraege])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $zeilen = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            '',
            'Sitemap: '.route('sitemap'),
        ];

        // Auf der Vorschau-Domain darf nichts indexiert werden. Das ist die
        // erste von drei Sperren; dazu kommen der noindex-Header und die
        // Passwortabfrage. Eine allein reicht erfahrungsgemäß nicht.
        if (! app()->environment('production')) {
            $zeilen = ['User-agent: *', 'Disallow: /'];
        }

        return response(implode("\n", $zeilen)."\n")
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
