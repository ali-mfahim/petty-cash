<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

if (config("app.cron_enable") == true) {
    Schedule::command('fetch-order')->everyFiveMinutes();
    Schedule::command('update-customer')->everyFiveMinutes();
}
