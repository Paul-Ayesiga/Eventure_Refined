<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Here is where you can define all of your scheduled tasks. In Laravel 12,
| scheduled tasks are registered here instead of in a Kernel.php file.
|
*/

Schedule::command('events:archive-past')
    ->daily()
    ->at('01:00')
    ->description('Archive past events');

Schedule::command('events:delete-archived')
    ->daily()
    ->at('02:00')
    ->description('Delete events that have been archived for more than 30 days');
