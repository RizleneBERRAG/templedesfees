<?php

namespace App\Http\Controllers;

use App\Models\Cat;

class CatController extends Controller
{
    public function index()
    {
        return view('pages.cats.index', [
            'chats' => Cat::publies()->with('healthTests')->get(),
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
