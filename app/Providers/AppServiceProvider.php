<?php

namespace App\Providers;

use App\Models\Cat;
use App\Models\Kitten;
use App\Observers\CatObserver;
use App\Observers\KittenObserver;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Dates en francais dans tout le site (translatedFormat).
        Carbon::setLocale('fr');

        Cat::observe(CatObserver::class);
        Kitten::observe(KittenObserver::class);
    }
}
