<?php declare(strict_types=1); 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        // Charger les helpers personnalisés
        if (file_exists(app_path('Helpers/NumberHelper.php'))) {
            require_once app_path('Helpers/NumberHelper.php');
        }
    }
}
