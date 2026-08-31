<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Review;
use App\Models\ServiceCategory;
use Illuminate\View\View;

/**
 * Die Seiten ohne eigene Fachlogik.
 *
 * Bewusst ein Controller statt Route::view(), weil die meisten dieser Seiten
 * Daten brauchen – Leistungen, Kundenstimmen, aktuelle Beiträge – und sie sonst
 * über Blade-Composer eingesammelt werden müssten.
 */
class PageController extends Controller
{
    public function start(): View
    {
        return view('seiten.start', [
            'projekte' => Project::featured()->limit(6)->get(),
            'beitraege' => Post::published()->with('category')->latest('published_at')->limit(3)->get(),
            'stimmen' => Review::visible()->get(),
            'gruppen' => ServiceCategory::with('services')->orderBy('position')->get(),
        ]);
    }

    public function leistungen(): View
    {
        return view('seiten.leistungen', [
            'gruppen' => ServiceCategory::with('services')->orderBy('position')->get(),
        ]);
    }

    public function ueberUns(): View
    {
        return view('seiten.ueber-uns');
    }

    public function kontakt(): View
    {
        return view('seiten.kontakt');
    }

    public function projektanfrage(): View
    {
        return view('seiten.projektanfrage');
    }

    public function termine(): View
    {
        return view('seiten.termine');
    }

    public function impressum(): View
    {
        return view('seiten.impressum');
    }

    public function datenschutz(): View
    {
        return view('seiten.datenschutz');
    }

    public function agb(): View
    {
        return view('seiten.agb');
    }
}
