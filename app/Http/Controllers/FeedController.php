<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

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

        return response()
            ->view('feeds.blog', compact('beitraege'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
