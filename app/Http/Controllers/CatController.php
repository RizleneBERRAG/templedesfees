<?php

namespace App\Http\Controllers;

use App\Enums\CatRole;
use App\Models\Cat;
use Illuminate\Http\Request;

class CatController extends Controller
{
    /**
     * Le catalogue des chats de l'elevage.
     *
     * Deux filtres, sur le role et sur le sexe, portes par l'URL plutot que
     * par du JavaScript : une vue filtree se partage, se met en favori et
     * s'indexe. Un filtre inconnu est ignore, il ne vide pas la page.
     */
    public function index(Request $request)
    {
        $chats = Cat::publies()->with('healthTests')->get();

        $role = CatRole::tryFrom((string) $request->query('role'));
        $sexe = in_array($request->query('sexe'), ['male', 'femelle'], true)
            ? $request->query('sexe')
            : null;

        $filtres = $chats->filter(fn (Cat $c) => (! $role || $c->role === $role)
            && (! $sexe || $c->sexe === $sexe));

        return view('pages.cats.index', [
            'chats'    => $filtres->values(),
            'total'    => $chats->count(),
            'parRole'  => $chats->countBy(fn (Cat $c) => $c->role->value),
            'parSexe'  => $chats->countBy('sexe'),
            'role'     => $role,
            'sexe'     => $sexe,
        ]);
    }

    public function show(Cat $cat)
    {
        abort_unless($cat->est_publie, 404);

        $cat->load(['healthTests', 'photos']);

        return view('pages.cats.show', [
            'chat'    => $cat,
            'portees' => $cat->portees()->withCount('kittens')->get(),
            'autres'  => Cat::publies()->whereKeyNot($cat->id)->get(),
        ]);
    }
}
