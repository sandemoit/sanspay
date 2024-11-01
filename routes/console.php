<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Schedule::command('get:product')->dailyAt('00:00');
Schedule::command('get:saldodigi')->dailyAt('00:00');
Schedule::command('midtrans:callback')->everyMinute();
