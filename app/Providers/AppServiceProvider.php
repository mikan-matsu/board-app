<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (!app()->runningInConsole()) {
            try {
                DB::statement("SET TIMEZONE TO 'Asia/Tokyo'");
            } catch (\Exception $e) {
                // ビルド時やDB未接続時は無視
            }
        }
    }
}
