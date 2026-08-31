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

    /**
     * Die Unterscheidung hängt an der Domain, nicht an der Umgebung.
     *
     * Die Vorschau läuft mit APP_ENV=production – sie soll sich schließlich
     * genauso verhalten wie das Original. Eine Prüfung auf die Umgebung wäre
     * dort also wirkungslos.
     *
     * Wichtig: Auf der Vorschau steht bewusst KEIN "Disallow: /". Das würde
     * zwar das Crawlen verbieten, aber nicht verhindern, dass eine verlinkte
     * Adresse im Index landet – und weil Google die Seite dann nicht abrufen
     * darf, sähe es den noindex-Header aus den Traefik-Labels nie. Beide
     * Sperren blockieren sich gegenseitig. Google muss die Seite lesen dürfen,
     * damit es sie draußen lassen kann.
     *
     * Der Unterschied zur Live-Fassung ist deshalb nur die fehlende
     * Sitemap-Zeile: die Vorschau lädt niemanden zum Indexieren ein.
     */
    public function robots(): Response
    {
        $liveDomain = 'nils-digital.de';
        $istLive = in_array(request()->getHost(), [$liveDomain, 'www.'.$liveDomain], true);

        $zeilen = ['User-agent: *', 'Allow: /', 'Disallow: /admin'];

        if ($istLive) {
            $zeilen[] = '';
            $zeilen[] = 'Sitemap: '.route('sitemap');
        } else {
            array_unshift($zeilen, '# Vorschau. Die Indexierung regelt der X-Robots-Tag-Header,');
            array_unshift($zeilen, '# nicht diese Datei – siehe Kommentar im SitemapController.');
        }

        return response(implode("\n", $zeilen)."\n")
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
