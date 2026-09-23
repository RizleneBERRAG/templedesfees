<?php

use Illuminate\Support\Facades\Schedule;

// Purge RGPD : dossiers adoptants (24 mois) et messages de contact (12 mois).
Schedule::command('rgpd:purge')->dailyAt('03:00');

/*
 * Les reservations perimees. Tous les matins, avant que l'eleveuse ouvre son
 * back-office : elle doit y trouver l'etat du jour, pas celui de la veille.
 */
Schedule::command('reservations:menage')->dailyAt('06:00');
