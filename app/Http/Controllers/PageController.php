<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use App\Models\Review;
use App\Models\ServiceCategory;
use App\Models\TeamMember;
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
            /*
             * Drei statt sechs.
             *
             * Die Startseite zeigt eine Auswahl, keinen Katalog – dafür gibt
             * es /projekte. Sechs volle Karten wiederholen nur, was die
             * Übersicht besser macht, und kosten viel Höhe; im Hero darüber
             * stehen ohnehin schon zwei Projekte.
             *
             * Bewusst nicht "die neuesten": bei Referenzen ist das kein
             * sinnvolles Kriterium – das beste Projekt ist selten das jüngste,
             * und ein Fertigstellungsdatum gibt es gar nicht. Welche drei es
             * sind, entscheidet die Redaktion über is_featured und position.
             */
            'projekte' => Project::featured()->limit(3)->get(),

            // Für den Link zur Übersicht. Wächst mit, während die Startseite
            // gleich hoch bleibt – die Zahl ist selbst ein Argument.
            'projekteGesamt' => Project::count(),
            'beitraege' => Post::published()->with('category')->latest('published_at')->limit(3)->get(),

            /*
             * Kundenstimmen kommen aus zwei Abfragen.
             *
             * Gezeigt wird eine zufällige Auswahl: es werden laufend mehr, und
             * würden sie alle untereinander stehen, wüchse die Startseite mit
             * jeder neuen Stimme weiter – gleichzeitig sähe jede Besucherin
             * ewig dieselben. Die Gesamtbewertung im Schema.org-Block muss
             * dagegen über *alle* sichtbaren laufen: eine reviewCount, die sich
             * bei jedem Seitenaufruf ändert, ist für Suchmaschinen ein
             * Warnzeichen.
             */
            'stimmen' => Review::vorzeigbar()->inRandomOrder()->limit(4)->get(),
            'stimmenGesamt' => Review::visible()->get(),

            /*
             * Die zwei Screenshots im Hero. Anders als das Referenz-Raster
             * darunter wechseln sie bei jedem Aufruf: dort geht es um
             * Bandbreite, hier nur um einen Blickfang, der beweist, dass es
             * echte laufende Projekte gibt.
             *
             * Zwei Bedingungen, beide aus dem Rahmen selbst begründet: ohne
             * Bild bliebe ein leeres Fenster stehen, das nach Attrappe aussieht.
             * Und die Adresszeile des Rahmens verspricht eine Seite, die man
             * besuchen kann – deshalb nur Projekte mit echter Adresse. Sonst
             * landet dort ein Herzensprojekt ohne eigene Website und der Rahmen
             * behauptet etwas, das nicht stimmt.
             *
             * Beides ist über die Redaktion steuerbar: wer ein Projekt im Hero
             * haben will, hinterlegt Bild und Adresse.
             */
            /*
             * Alle geeigneten, nicht nur zwei: die beiden Fenster im Hero
             * blättern durch diese Liste, statt sie nur beim Seitenaufruf zu
             * würfeln. Sechs ist die Obergrenze, für die es im Stylesheet
             * einen Takt gibt – und mehr Bilder über der Falz will man
             * ohnehin nicht laden.
             */
            'heldenprojekte' => Project::featured()
                ->whereNotNull('image')
                ->where('link', 'like', 'http%')
                ->reorder()->inRandomOrder()->limit(6)->get(),

            'gruppen' => ServiceCategory::with('services')->orderBy('position')->get(),
        ]);
    }

    public function leistungen(): View
    {
        return view('seiten.leistungen', [
            'gruppen' => ServiceCategory::with('services')->orderBy('position')->get(),
        ]);
    }

    public function team(): View
    {
        return view('seiten.team', [
            'team' => TeamMember::visible()->get(),
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
