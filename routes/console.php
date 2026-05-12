<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Auto-Alpha Scheduler
|--------------------------------------------------------------------------
| Setiap hari pukul 23:59 WIB, sistem otomatis menandai guru yang
| belum absen sebagai Alpha.
|
| Aktifkan cron di server/cPanel:
|   * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
|--------------------------------------------------------------------------
*/
Schedule::command('absensi:auto-alpha')
    ->dailyAt('23:59')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping()
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/auto-alpha.log'));