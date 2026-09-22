<?php

use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\KittenController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/',                        [PageController::class, 'home'])->name('home');

Route::get('/chatons',                 [KittenController::class, 'index'])->name('kittens.index');
Route::get('/chatons/{kitten}',        [KittenController::class, 'show'])->name('kittens.show');

Route::get('/elevage',                 [CatController::class, 'index'])->name('cats.index');
Route::get('/elevage/{cat}',           [CatController::class, 'show'])->name('cats.show');

Route::get('/le-maine-coon',               [PageController::class, 'breed'])->name('breed');
Route::get('/galerie',                 [PageController::class, 'gallery'])->name('gallery');
Route::get('/questions',               [PageController::class, 'faq'])->name('faq');
Route::get('/contact',                 [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,60')   // 5 messages par heure et par IP
    ->name('contact.store');
Route::post('/avis', [ReviewController::class, 'store'])
    ->middleware('throttle:3,60')   // 3 avis par heure et par IP
    ->name('reviews.store');

Route::get('/mentions-legales',        [PageController::class, 'legal'])->name('legal');

/*
 * Sitemap et robots.txt servis par l'application : la directive Sitemap exige
 * une adresse absolue, qu'un fichier statique figerait sur le domaine du jour
 * ou il a ete ecrit.
 */
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt',  [SitemapController::class, 'robots'])->name('robots');

/*
 * Anciennes adresses du site WordPress, relevees dans l'audit. Redirection
 * permanente : les liens deja partages, les signets et les resultats de
 * recherche existants ne doivent pas tomber dans le vide le jour de la bascule.
 * Une 301 transmet aussi au moteur le peu de reputation acquise par l'ancienne
 * adresse. La liste vit dans config/chatterie.php, partagee avec la page 404 qui
 * traite les cas non listes.
 */
foreach (config('chatterie.anciennes_urls', []) as $ancienne => $destination) {
    Route::get('/'.$ancienne, fn () => redirect()->route($destination, [], 301))
        ->name('ancienne.'.$ancienne);
}

Route::get('/adopter',                 [AdoptionController::class, 'create'])->name('adoption.create');
Route::post('/adopter', [AdoptionController::class, 'store'])
    ->middleware('throttle:5,60')   // 5 demandes par heure et par IP
    ->name('adoption.store');
