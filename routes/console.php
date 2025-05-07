<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('app:update-bl')->everyFiveMinutes();
Schedule::command('app:update-articles')->everyFiveMinutes();
Schedule::command('app:update-contact-relance')->everyFiveMinutes();

Schedule::command('app:update-contact')->everyTwoMinutes();
//Schedule::command('app:update-devis')->everyTwoMinutes();
//Schedule::command('app:update-societes')->everyTwoMinutes();


Schedule::command('app:get-attente-matiere-premiere')->dailyAt('05:00');
