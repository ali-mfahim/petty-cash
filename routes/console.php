<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

if (enableCron() == true) {
    // Schedule::command('fetch-order')->everyFiveMinutes();
    // Schedule::command('update-customer')->everyFiveMinutes();
    // Schedule::command('collection-products')->everyMinute();
    // Schedule::command('match-products')->everyMinute();
    // Schedule::command('export-collections')->everyMinute();
    // Schedule::command('link-products-to-collection')->everyMinute();
}
