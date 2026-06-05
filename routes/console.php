<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Process failed payout refunds every day at 9:00 AM
// Schedule::command('payout:process-refunds')->dailyAt('09:00');
Schedule::command('payout:process-refunds')->everyMinute();

