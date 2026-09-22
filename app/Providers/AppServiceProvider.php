<?php

namespace App\Providers;

use App\Models\Kitten;
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

        Kitten::observe(KittenObserver::class);
    }
}
