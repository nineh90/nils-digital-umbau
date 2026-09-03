<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlogController extends Controller
{
    /*
     * Zwölf Beiträge je Seite.
     *
     * Sechs waren von der alten Übersicht übernommen und füllten auf dem
     * Desktop nur zwei Reihen – man blätterte mehr, als man las.
     *
     * Zwölf und nicht fünfzehn, weil das Raster die Zahl vorgibt: drei
     * Spalten ab lg, zwei ab sm. Zwölf geht in beiden auf (vier Reihen
     * beziehungsweise sechs), fünfzehn lässt bei zwei Spalten eine Kachel
     * allein in der letzten Reihe stehen. Wer die Zahl ändert, nimmt
     * deshalb ein Vielfaches von sechs.
     */
    private const PRO_SEITE = 12;

    public function index(Request $request): View
    {
        $beitraege = Post::published()
            ->with(['category', 'product'])
            ->latest('published_at')
            ->paginate(self::PRO_SEITE)
            ->withQueryString();

        return view('blog.index', [
            'beitraege' => $beitraege,
            'kategorien' => Category::whereHas('posts', fn ($q) => $q->published())
                ->withCount(['posts' => fn ($q) => $q->published()])
                ->orderBy('name')
                ->get(),
            'aktiveKategorie' => null,
        ]);
    }

    public function kategorie(Category $category): View
    {
        $beitraege = Post::published()
            ->where('category_id', $category->id)
            ->with(['category', 'product'])
            ->latest('published_at')
            ->paginate(self::PRO_SEITE)
            ->withQueryString();

        return view('blog.index', [
            'beitraege' => $beitraege,
            'kategorien' => Category::whereHas('posts', fn ($q) => $q->published())
                ->withCount(['posts' => fn ($q) => $q->published()])
                ->orderBy('name')
                ->get(),
            'aktiveKategorie' => $category,
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->status === 'published' && $post->published_at?->isPast(), 404);

        $post->load(['category', 'links', 'product']);

        // Nachbarbeiträge für die Weiterleitung am Textende. Interne
        // Verlinkung ist einer der Punkte, an denen die alte Seite nichts tat:
        // ein Beitrag war eine Sackgasse.
        $weitere = Post::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')
            ->limit(3)
            ->get();

        if ($weitere->count() < 3) {
            $weitere = $weitere->concat(
                Post::published()
                    ->where('id', '!=', $post->id)
                    ->whereNotIn('id', $weitere->pluck('id'))
                    ->latest('published_at')
                    ->limit(3 - $weitere->count())
                    ->get()
            );
        }

        return view('blog.show', compact('post', 'weitere'));
    }

    /**
     * Weiterleitung der alten Adressen /pages/blog-post.html?id=N.
     *
     * Als Route und nicht als Rewrite-Regel im Webserver, weil sich das testen
     * lässt: der Test in RedirectTest geht die vollständige Liste aller
     * Alt-Adressen durch. Eine RewriteCond auf den Query-String könnte das nicht.
     */
    public function legacy(Request $request): RedirectResponse
    {
        $post = Post::where('legacy_id', $request->query('id'))->first();

        return redirect()->to(
            $post ? route('blog.show', $post) : route('blog.index'),
            301
        );
    }
}
