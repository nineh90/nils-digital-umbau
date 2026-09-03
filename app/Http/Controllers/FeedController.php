<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

/**
 * RSS-Feed des Blogs.
 *
 * Auf der alten Seite gab es keinen – wer regelmäßig mitlesen wollte, musste
 * die Seite von Hand aufrufen. Kostet als Route fast nichts und macht die
 * Beiträge für Feedreader und Aggregatoren auffindbar.
 */
class FeedController extends Controller
{
    public function blog(): Response
    {
        $beitraege = Post::published()
            ->latest('published_at')
            ->limit(30)
            ->get();

        /*
         * application/xml und nicht application/rss+xml – das sieht nach dem
         * unspezifischeren Typ aus und ist trotzdem Absicht: Chrome wendet das
         * XSL-Stylesheet auf application/rss+xml nicht an und zeigt weiter
         * Quelltext. Genau das sollte die Vorlage ja abstellen.
         *
         * Für Feedreader macht es keinen Unterschied, die erkennen den Feed am
         * <rss>-Wurzelelement. Und die Auszeichnung, auf die es bei der Suche
         * ankommt, steht ohnehin woanders: das <link rel="alternate"
         * type="application/rss+xml"> im Kopf jeder Seite ist unverändert.
         */
        return response()
            ->view('feeds.blog', compact('beitraege'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * Die Ansicht des Feeds im Browser.
     *
     * Als Route und nicht als Datei unter public/, weil es auf die Kopfzeile
     * ankommt: Browser wenden ein Stylesheet nur an, wenn es als XML-Typ
     * ausgeliefert wird. Ob Apache das für .xsl von sich aus richtig macht,
     * hängt an seiner MIME-Tabelle – hier steht es fest und ist getestet.
     *
     * Feedreader sehen davon nichts, die Verarbeitungsanweisung im Feed
     * interessiert nur Browser.
     */
    public function stylesheet(): Response
    {
        return response(
            File::get(resource_path('feeds/blog.xsl')),
            200,
            [
                'Content-Type' => 'text/xsl; charset=UTF-8',
                'Cache-Control' => 'public, max-age=86400',
            ]
        );
    }
}
