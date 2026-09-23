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
            'dates'      => Setting::get('hommage.dates'),
            'fille'      => $filleSlug ? Cat::where('slug', $filleSlug)->first() : null,
            'principale' => \App\Support\PhotosOlimpia::principale(),
            'suivantes'  => \App\Support\PhotosOlimpia::suivantes(),
        ]);
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
