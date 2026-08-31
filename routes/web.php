<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\KontaktController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
 * Öffentliche Seite.
 *
 * Die Wurzel gehört hier – anders als im Ticketsystem – der öffentlichen Seite.
 * Filament liegt bewusst auf /admin (siehe AdminPanelProvider), damit sich
 * Redaktion und Website nicht um "/" streiten.
 */
Route::get('/', [PageController::class, 'start'])->name('start');
Route::get('/leistungen', [PageController::class, 'leistungen'])->name('leistungen');
Route::get('/ueber-uns', [PageController::class, 'ueberUns'])->name('ueber-uns');
Route::view('/team', 'seiten.team')->name('team');

Route::get('/kontakt', [PageController::class, 'kontakt'])->name('kontakt');
Route::post('/kontakt', [KontaktController::class, 'senden'])->name('kontakt.senden');
Route::get('/projektanfrage', [PageController::class, 'projektanfrage'])->name('projektanfrage');
Route::get('/termine', [PageController::class, 'termine'])->name('termine');

Route::get('/impressum', [PageController::class, 'impressum'])->name('impressum');
Route::get('/datenschutz', [PageController::class, 'datenschutz'])->name('datenschutz');
Route::get('/agb', [PageController::class, 'agb'])->name('agb');

/*
 * Blog.
 *
 * Reihenfolge beachten: /blog/kategorie/{slug} und /blog/feed müssen vor
 * /blog/{post} stehen, sonst schluckt der Beitrags-Platzhalter beide Wörter.
 */
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/feed', [FeedController::class, 'blog'])->name('blog.feed');
Route::get('/blog/kategorie/{category}', [BlogController::class, 'kategorie'])->name('blog.kategorie');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

/* Projekte. */
Route::get('/projekte', [ProjectController::class, 'index'])->name('projekte');
Route::get('/projekte/{project}', [ProjectController::class, 'show'])->name('projekte.show');

/* Maschinenlesbares. */
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

/*
 * Kurzwege ins Ticketsystem.
 *
 * Bewusst 301-Weiterleitungen statt Proxy: Filament baut seine URLs aus
 * APP_URL. Unter fremder Domain durchgereicht brächen Anmeldung,
 * Sitzungs-Cookies und die Anhang-Downloads.
 */
Route::redirect('/kunde', 'https://intern.nils-digital.de/kunde', 301)->name('kundenbereich');
Route::redirect('/intern', 'https://intern.nils-digital.de', 301)->name('internbereich');

/*
 * Weiterleitungen der alten Adressen.
 *
 * Jede 301 zeigt direkt auf ihr Endziel, nie über eine Zwischenstation –
 * Weiterleitungsketten kosten Rankings. Die Liste entspricht
 * database/legacy/url-map.csv, ein Test geht sie vollständig durch.
 */
Route::get('/pages/blog-post.html', [BlogController::class, 'legacy']);

foreach ([
    '/index.html' => '/',
    '/pages/webdesign-leistung.html' => '/leistungen',
    '/pages/projekte.html' => '/projekte',
    '/pages/blog.html' => '/blog',
    '/pages/ueber-uns.html' => '/ueber-uns',
    '/pages/team.html' => '/team',
    '/pages/kontakt.html' => '/kontakt',
    '/pages/projektfragebogen.html' => '/projektanfrage',
    '/pages/reservierung.html' => '/termine',
    '/pages/impressum.html' => '/impressum',
    '/pages/datenschutz.html' => '/datenschutz',
    '/pages/agb.html' => '/agb',
] as $alt => $neu) {
    Route::permanentRedirect($alt, $neu);
}

/*
 * Seiten, die es nicht mehr gibt.
 *
 * sunnycam und shop waren in der alten robots.txt bewusst für Suchmaschinen
 * gesperrt. Der Shop lief über einen Spreadshop-Einbau, SunnyCam war eine
 * Unterhaltungsseite – beide werden vorerst nicht neu gebaut. Damit alte Links
 * nicht ins Leere laufen, zeigen sie auf die Startseite.
 */
Route::permanentRedirect('/pages/sunnycam.html', '/');
Route::permanentRedirect('/pages/shop.html', '/');
