<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Isi boot() yang lama tetap di sini
        // (jangan hapus kode yang sudah ada sebelumnya)

        // Tambahan force HTTPS
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}