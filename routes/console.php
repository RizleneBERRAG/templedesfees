<?php

use Illuminate\Support\Facades\Schedule;

// Purge RGPD : dossiers adoptants (24 mois) et messages de contact (12 mois).
Schedule::command('rgpd:purge')->dailyAt('03:00');
