<?php

use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\KittenController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/',                        [PageController::class, 'home'])->name('home');

Route::get('/chatons',                 [KittenController::class, 'index'])->name('kittens.index');
Route::get('/chatons/{kitten}',        [KittenController::class, 'show'])->name('kittens.show');

Route::get('/elevage',                 [CatController::class, 'index'])->name('cats.index');
Route::get('/elevage/{cat}',           [CatController::class, 'show'])->name('cats.show');

Route::get('/le-maine-coon',               [PageController::class, 'breed'])->name('breed');

Route::get('/articles',                [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}',      [ArticleController::class, 'show'])->name('articles.show');
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

/*
 * La reservation d'un chaton.
 *
 * Ces adresses ne sont dans aucun menu et ne s'indexent pas : on y arrive par
 * un lien envoye a une famille precise, apres la visite. Le jeton de quarante
 * caracteres tient lieu de clef — demander la creation d'un compte pour verser
 * un acompte ferait perdre la moitie des familles.
 */
Route::get('/reservation/{jeton}', [ReservationController::class, 'montrer'])
    ->name('reservation.montrer');

Route::post('/reservation/{jeton}/payer', [ReservationController::class, 'payer'])
    ->middleware('throttle:10,60')
    ->name('reservation.payer');

Route::get('/reservation/{jeton}/merci', [ReservationController::class, 'merci'])
    ->name('reservation.merci');

/*
 * Les deux documents de la reservation.
 *
 * Le contrat existe des que la reservation est creee ; la facture n'existe
 * qu'une fois l'acompte encaisse — une facture d'acompte atteste un versement,
 * elle ne l'annonce pas.
 *
 * Ils sont derriere le meme jeton que la page de paiement, et pas derriere une
 * connexion : la famille les ouvre depuis le lien qu'elle a recu, l'eleveuse
 * depuis sa fiche. Une seule adresse, un seul document, aucune copie qui
 * diverge.
 */
Route::get('/reservation/{jeton}/contrat', [ReservationController::class, 'contrat'])
    ->name('reservation.contrat');

Route::get('/reservation/{jeton}/facture', [ReservationController::class, 'facture'])
    ->name('reservation.facture');

/*
 * La notification de paiement, appelee par Stripe de serveur a serveur. Elle
 * fait foi : c'est elle qui marque l'acompte recu, et non le retour du
 * navigateur, qu'une famille peut fermer avant qu'il arrive.
 */
Route::post('/paiement/notification', [ReservationController::class, 'webhook'])
    ->name('paiement.notification');
