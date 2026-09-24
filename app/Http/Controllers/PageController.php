<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use App\Models\Faq;
use App\Models\Kitten;
use App\Models\Litter;
use App\Models\Photo;
use App\Models\Setting;

class PageController extends Controller
{
    public function home()
    {
        $portee = Litter::publiees()
            ->with(['pere', 'mere', 'kittens' => fn ($q) => $q->publies()])
            ->orderByDesc('date_naissance')
            ->first();

        $chats = Cat::publies()->with('healthTests')->get();

        return view('pages.home', [
            'portee'   => $portee,
            'chatons'  => $portee?->kittens ?? collect(),
            'chats'    => $chats,
            'nbDispo'  => Kitten::publies()->disponibles()->count(),
            'photos'   => Photo::publiees()->inRandomOrder()->limit(12)->get(),
            // Le chapitre « registre » de l'accueil montre un dossier reel plutot
            // qu'un exemple : on prend le premier chat qui en a un a montrer.
            'vitrine'  => $chats->first(fn (Cat $c) => $c->healthTests->isNotEmpty()),
        ]);
    }

    public function breed()
    {
        return view('pages.breed');
    }

    /**
     * En memoire d'Olimpia.
     *
     * Le texte et les dates vivent en reglage : c'est l'eleveur qui parle, a
     * la premiere personne, et il doit pouvoir reprendre ses propres mots sans
     * demander une intervention. Le lien vers sa fille n'apparait que s'il a
     * designe laquelle — on ne devine pas une filiation.
     */
    public function hommage()
    {
        $filleSlug = Setting::get('hommage.fille');

        return view('pages.hommage', [
            'texte'      => Setting::get('hommage.texte'),
            'mot'        => Setting::get('hommage.mot'),
            'adieu'      => Setting::get('hommage.adieu'),
            'dates'      => Setting::get('hommage.dates'),
            'fille'      => $filleSlug ? Cat::where('slug', $filleSlug)->first() : null,
            'principale' => \App\Support\PhotosOlimpia::principale(),
            'suivantes'  => \App\Support\PhotosOlimpia::suivantes(),
        ]);
    }

    /**
     * Le souvenir d'Olimpia, a imprimer.
     *
     * Meme mise en page que le contrat et la facture — c'est la seule du site
     * qui sache faire du papier — mais sans reservation et sans mentions
     * obligatoires : ce n'est pas une piece comptable, c'est un faire-part.
     */
    public function souvenir()
    {
        return view('documents.olimpia', [
            'portrait' => \App\Support\PhotosOlimpia::principale(),
            'mot'      => Setting::get('hommage.mot'),
            // Le premier paragraphe du texte de seuil raconte le mot ; sur la
            // feuille, le mot est déjà en grand juste au-dessus. On n'imprime
            // donc que ce qui vient après : l'entendre deux fois de suite lui
            // enlève ce qu'il a.
            'texte'    => $this->sansLePremierParagraphe(Setting::get('hommage.seuil')),
            'adieu'    => Setting::get('hommage.adieu'),
            'dates'    => Setting::get('hommage.dates'),
            'retour'   => ['url' => route('hommage'), 'libelle' => 'Revenir à sa page'],
            'mentions' => false,
        ]);
    }

    /**
     * Un texte, moins son premier paragraphe.
     *
     * Le tri se fait par position et non par contenu : chercher le mot dans le
     * texte ne marche pas, puisqu'il y est raconte plutot que cite — « on
     * n'avait qu'un mot a dire » ne contient pas « le poulette ».
     *
     * S'il n'y a qu'un paragraphe, on le garde : mieux vaut une redite qu'une
     * feuille muette.
     */
    private function sansLePremierParagraphe(?string $texte): ?string
    {
        if (blank($texte)) {
            return $texte;
        }

        $paragraphes = preg_split('/\R{2,}/u', trim($texte));

        return count($paragraphes) > 1
            ? implode(PHP_EOL.PHP_EOL, array_slice($paragraphes, 1))
            : $texte;
    }

    public function gallery()
    {
        $photos = Photo::publiees()->orderBy('ordre')->get();

        return view('pages.gallery', [
            'photos'      => $photos,
            'categories'  => $photos->pluck('categorie')->filter()->unique()->values(),
        ]);
    }

    public function faq()
    {
        return view('pages.faq', ['faqs' => Faq::publiees()->get()]);
    }

    public function legal()
    {
        return view('pages.legal');
    }
}
