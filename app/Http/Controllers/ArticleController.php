<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::publies()->get();

        $categorie = $request->query('categorie');
        $retenus = $categorie
            ? $articles->where('categorie', $categorie)
            : $articles;

        return view('pages.articles.index', [
            'articles'   => $retenus->values(),
            'total'      => $articles->count(),
            'categories' => $articles->pluck('categorie')->filter()->unique()->sort()->values(),
            'compte'     => $articles->countBy('categorie'),
            'categorie'  => $categorie,
        ]);
    }

    /**
     * Le binding ne resout que sur le slug : un article en brouillon ou
     * programme pour plus tard renvoie un 404, jamais une page a moitie prete.
     */
    public function show(Article $article)
    {
        abort_unless($article->estPublie(), 404);

        return view('pages.articles.show', [
            'article' => $article,
            'autres'  => Article::publies()->whereKeyNot($article->id)->limit(3)->get(),
        ]);
    }
}
