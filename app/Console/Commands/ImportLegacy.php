<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Übernimmt den Inhalt der alten statischen Seite aus legacy/ in die Datenbank.
 *
 * Beiträge werden über legacy_id wiedererkannt – dieses Feld trägt die
 * 301-Weiterleitungen von /pages/blog-post.html?id=N und darf deshalb nie neu
 * vergeben werden.
 *
 * Wichtig: Ohne --ueberschreiben werden vorhandene Datensätze NICHT angefasst.
 * Sobald in der Redaktion gearbeitet wird, ist die Datenbank die Wahrheit und
 * nicht mehr die JSON-Datei aus legacy/. Ein unbedachter zweiter Lauf würde
 * sonst jede dort gemachte Korrektur zurückdrehen – und das ohne Warnung, weil
 * der Befehl ja "nur importiert". So bleibt er gefahrlos wiederholbar und
 * ergänzt nur, was fehlt.
 */
class ImportLegacy extends Command
{
    protected $signature = 'nd:import-legacy
                            {--ohne-bilder : Bilder nicht nach public/ kopieren}
                            {--ueberschreiben : Vorhandene Datensätze auf den Stand aus legacy/ zurücksetzen}';

    protected $description = 'Importiert Beiträge, Projekte, Leistungen und Kundenstimmen aus legacy/';

    /**
     * Badge-Farben aus der alten legacy/css/main.css.
     *
     * Dort entstand die Farbe aus einer CSS-Klasse, die aus dem Kategorienamen
     * gebaut wurde. Bei "Lernsoftware - Lerndex" führte das zu drei
     * Bindestrichen (.cat-lernsoftware---lerndex) – fehlte die Regel, fiel das
     * Badge unbemerkt auf transparentes Schwarz zurück. Jetzt steht die Farbe
     * am Datensatz und eine neue Kategorie kann sie im Admin bekommen.
     */
    private const FARBEN = [
        'Allgemein' => ['rgba(120, 120, 140, 0.6)', null],
        'Community' => ['rgba(34, 197, 94, 0.6)', null],
        'Kooperationen' => ['rgba(13, 148, 136, 0.7)', null],
        'Lernsoftware - Lerndex' => ['rgba(99, 102, 241, 0.7)', null],
        'Pflegesoftware - Pflegedex' => ['rgba(190, 40, 75, 0.75)', null],
        'Projekte' => ['rgba(255, 152, 0, 0.75)', null],
        'Shop' => ['rgba(0, 200, 215, 0.7)', '#0a1a1f'],
        'SunnyCam' => ['rgba(255, 105, 180, 0.75)', null],
        'Über mich' => ['rgba(140, 100, 200, 0.65)', null],
    ];

    private string $legacy;

    private int $uebersprungen = 0;

    public function handle(): int
    {
        $this->legacy = base_path('legacy');

        if (! File::isDirectory($this->legacy)) {
            $this->error('Verzeichnis legacy/ nicht gefunden.');

            return self::FAILURE;
        }

        $this->kategorien();
        $this->beitraege();
        $this->projekte();
        $this->leistungen();
        $this->kundenstimmen();

        if (! $this->option('ohne-bilder')) {
            $this->bilder();
        }

        $this->verknuepfungen();
        $this->pruefung();

        $this->newLine();

        if ($this->uebersprungen > 0) {
            $this->line("Unveraendert:   {$this->uebersprungen} vorhandene Datensaetze");
            $this->comment('Mit --ueberschreiben wuerden sie auf den Stand aus legacy/ zurueckgesetzt.');
        }

        $this->info('Import abgeschlossen.');

        return self::SUCCESS;
    }

    /**
     * Verbindet Beiträge mit den Projekten, um die es geht.
     *
     * Der Altbestand kennt diese Verbindung nicht – Beiträge und Projektkarten
     * standen unverbunden nebeneinander. Hier wird sie einmalig hergestellt:
     * Produktreihen über ihre Kategorie, Einzelprojekte über ein Stichwort im
     * Titel. Danach ist die Zuordnung in der Redaktion frei bearbeitbar,
     * deshalb wird nur ergänzt (syncWithoutDetaching) und nichts überschrieben.
     */
    private function verknuepfungen(): void
    {
        $ueberKategorie = [
            'lerndex' => 'Lernsoftware - Lerndex',
            'pflegedex' => 'Pflegesoftware - Pflegedex',
            'sunnycam' => 'SunnyCam',
        ];

        $ueberTitel = [
            'fahrlehrerin-sarah' => 'Fahrlehrerin Sarah',
            'das-landhaus' => 'Landhaus',
            'crazyfamily' => 'CRAZYFAMILY',
        ];

        $anzahl = 0;

        foreach ($ueberKategorie as $projektSlug => $kategorieName) {
            if (! $projekt = Project::where('slug', $projektSlug)->first()) {
                continue;
            }

            $ids = Post::whereHas('category', fn ($q) => $q->where('name', $kategorieName))->pluck('id');
            $projekt->posts()->syncWithoutDetaching($ids);
            $anzahl += $ids->count();
        }

        foreach ($ueberTitel as $projektSlug => $stichwort) {
            if (! $projekt = Project::where('slug', $projektSlug)->first()) {
                continue;
            }

            $ids = Post::where('title', 'like', "%{$stichwort}%")->pluck('id');
            $projekt->posts()->syncWithoutDetaching($ids);
            $anzahl += $ids->count();
        }

        $this->line("Verknüpfungen:  {$anzahl} Beitrag-zu-Projekt");
    }

    /**
     * Meldet Verweise, die ins Leere zeigen.
     *
     * Im Altbestand steckt mindestens ein solcher Fall: Beitrag 21 bewirbt eine
     * Onepager-Demo unter /info/onepager/, die es weder im Repo noch auf der
     * Live-Seite gibt – Bild und Link liefern beide 404. Solche Reste sollen
     * beim Import sichtbar werden, statt still mitzuwandern.
     */
    private function pruefung(): void
    {
        $funde = [];

        foreach (Post::whereNotNull('hero_image')->get() as $post) {
            if (! File::exists(public_path($post->hero_image))) {
                $funde[] = "Beitrag {$post->legacy_id} ({$post->slug}): Bild fehlt – {$post->hero_image}";
            }
        }

        foreach (Project::whereNotNull('image')->get() as $projekt) {
            if (! File::exists(public_path($projekt->image))) {
                $funde[] = "Projekt {$projekt->slug}: Bild fehlt – {$projekt->image}";
            }
        }

        if ($funde === []) {
            return;
        }

        $this->newLine();
        $this->warn('Verweise ins Leere ('.count($funde).'):');
        foreach ($funde as $fund) {
            $this->warn("  {$fund}");
        }
    }

    /**
     * Anlegen – oder nur mit --ueberschreiben aktualisieren.
     *
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $model
     */
    private function sichern(string $model, array $schluessel, array $werte)
    {
        $vorhanden = $model::where($schluessel)->first();

        if ($vorhanden === null) {
            return $model::create($schluessel + $werte);
        }

        if ($this->option('ueberschreiben')) {
            $vorhanden->update($werte);
        } else {
            $this->uebersprungen++;
        }

        return $vorhanden;
    }

    private function json(string $datei): array
    {
        return json_decode(File::get("{$this->legacy}/assets/data/{$datei}"), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * Slug in deutscher Schreibweise: ä wird ae, nicht a.
     *
     * Str::slug() ohne Sprachangabe macht aus "gemütlich" ein "gemutlich".
     * Die dritte Stelle ('de') ist deshalb nicht optional – die erzeugten
     * Slugs müssen exakt der abgestimmten URL-Liste entsprechen, sonst zeigen
     * die 301-Weiterleitungen ins Leere.
     */
    private function slug(string $text): string
    {
        // Punkte vorher zu Bindestrichen: Str::slug wirft sie ersatzlos weg und
        // macht aus "nils-digital.de" ein "nils-digitalde" – zwei Wörter zu
        // einem verschmolzen, schlecht lesbar und schlechter für die Suche.
        $text = str_replace('.', '-', $text);

        return Str::slug($text, '-', 'de');
    }

    /** Bildpfade standen relativ zu pages/, also als ../assets/… */
    private function pfad(?string $pfad): ?string
    {
        if (blank($pfad)) {
            return null;
        }

        return ltrim(str_replace('../', '', $pfad), '/');
    }

    private function kategorien(): void
    {
        $namen = collect($this->json('blog.json'))->pluck('category')->unique()->sort()->values();

        foreach ($namen as $i => $name) {
            [$farbe, $textfarbe] = self::FARBEN[$name] ?? [null, null];

            $this->sichern(Category::class,
                ['slug' => $this->slug($name)],
                ['name' => $name, 'color' => $farbe, 'text_color' => $textfarbe, 'position' => $i]
            );

            if ($farbe === null) {
                $this->warn("  Kategorie ohne Farbe: {$name}");
            }
        }

        $this->line('Kategorien:     '.$namen->count());
    }

    private function beitraege(): void
    {
        $beitraege = collect($this->json('blog.json'))->sortBy('id');
        $vergeben = [];
        $neu = 0;

        foreach ($beitraege as $alt) {
            $slug = $this->slug($alt['title']);

            // Gleiche Titel gab es bei den Shop-Beiträgen. Der erste behält den
            // Slug, jeder weitere bekommt eine Nummer angehängt.
            if (isset($vergeben[$slug])) {
                $slug .= '-'.(++$vergeben[$slug]);
            } else {
                $vergeben[$slug] = 1;
            }

            $kategorie = Category::where('slug', $this->slug($alt['category']))->first();

            $post = $this->sichern(Post::class,
                ['legacy_id' => $alt['id']],
                [
                    'category_id' => $kategorie?->id,
                    'slug' => $slug,
                    'title' => $alt['title'],
                    'teaser' => $alt['teaser'] ?? '',
                    'content' => $alt['content'] ?? '',
                    // "image" in der Einzahl steht in fünf Beiträgen, wurde aber
                    // von keinem Skript gelesen und zeigt teils auf Dateien, die
                    // es nicht gibt. Karteileiche, wird nicht übernommen.
                    'hero_image' => $this->pfad($alt['images'][0] ?? null),
                    'thumb_fit' => $alt['thumbFit'] ?? null,
                    'status' => 'published',
                    'published_at' => $alt['date'],
                ]
            );

            // Ohne --ueberschreiben in Ruhe lassen: sonst verschwinden in der
            // Redaktion ergaenzte Schaltflaechen bei jedem Lauf.
            if (! $this->option('ueberschreiben') && $post->exists && $post->wasRecentlyCreated === false) {
                $neu++;

                continue;
            }

            $post->links()->delete();
            foreach ($alt['links'] ?? [] as $i => $link) {
                $post->links()->create([
                    'url' => $link['url'],
                    'label' => $link['text'],
                    'position' => $i,
                ]);
            }

            $post->product()->delete();
            if ($p = $alt['product'] ?? null) {
                $post->product()->create([
                    'name' => $p['name'],
                    'image' => $this->pfad($p['image'] ?? null),
                    'price' => $p['price'] ?? null,
                    'currency' => $p['currency'] ?? 'EUR',
                    'availability' => $p['availability'] ?? null,
                    'shop_url' => $p['shopUrl'] ?? null,
                    'type' => $p['type'] ?? null,
                ]);
            }

            $neu++;
        }

        $this->line("Beiträge:       {$neu}");
    }

    private function projekte(): void
    {
        $projekte = collect($this->json('projects.json'));

        foreach ($projekte as $i => $alt) {
            $this->sichern(Project::class,
                ['slug' => $alt['id']],
                [
                    'title' => $alt['title'],
                    'type' => $alt['type'] ?? null,
                    'status' => $alt['status'] ?? null,
                    'description' => $alt['description'] ?? '',
                    'image' => $this->pfad($alt['image'] ?? null),
                    'image_fit' => $alt['imageFit'] ?? 'contain',
                    'link' => $alt['link'] ?? null,
                    'is_internal' => (bool) ($alt['internal'] ?? false),
                    // Bisher standen die Karten der Startseite hart in
                    // index.html und mussten doppelt gepflegt werden. Ab jetzt
                    // entscheidet dieses Feld.
                    'is_featured' => true,
                    'tags' => $alt['tags'] ?? [],
                    'position' => $i,
                ]
            );
        }

        $this->line('Projekte:       '.$projekte->count());
    }

    private function leistungen(): void
    {
        $gruppen = collect($this->json('services.json'));
        $anzahl = 0;

        foreach ($gruppen as $i => $gruppe) {
            $kategorie = $this->sichern(ServiceCategory::class,
                ['slug' => $this->slug($gruppe['category'])],
                ['name' => $gruppe['category'], 'position' => $i]
            );

            foreach ($gruppe['services'] as $j => $leistung) {
                $this->sichern(Service::class,
                    ['service_category_id' => $kategorie->id, 'slug' => $leistung['id']],
                    [
                        'name' => $leistung['name'],
                        'description' => $leistung['description'] ?? '',
                        'price' => $leistung['price'] ?? null,
                        'unit' => $leistung['unit'] ?? null,
                        'icon' => $leistung['icon'] ?? null,
                        'position' => $j,
                    ]
                );
                $anzahl++;
            }
        }

        $this->line("Leistungen:     {$anzahl} in {$gruppen->count()} Gruppen");
    }

    private function kundenstimmen(): void
    {
        $stimmen = collect($this->json('reviews.json'));

        foreach ($stimmen as $i => $alt) {
            $this->sichern(Review::class,
                ['name' => $alt['name'], 'text' => $alt['text']],
                [
                    'rating' => $alt['rating'] ?? null,
                    'source' => $alt['source'] ?? null,
                    'project' => $alt['project'] ?? null,
                    'position' => $i,
                ]
            );
        }

        $this->line('Kundenstimmen:  '.$stimmen->count());
    }

    /** Bilder aus legacy/assets/images nach public/assets/images spiegeln. */
    private function bilder(): void
    {
        $von = "{$this->legacy}/assets/images";
        $nach = public_path('assets/images');

        if (! File::isDirectory($von)) {
            $this->warn('Keine Bilder in legacy/assets/images gefunden.');

            return;
        }

        File::ensureDirectoryExists($nach);
        File::copyDirectory($von, $nach);

        $this->line('Bilder:         '.count(File::allFiles($nach)).' Dateien nach public/assets/images');
    }
}
