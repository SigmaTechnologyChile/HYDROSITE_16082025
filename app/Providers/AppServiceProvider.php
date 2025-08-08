<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::except([
            'payment-response/*'
        ]);
        
        // Directiva de Blade para formatear dinero
        \Illuminate\Support\Facades\Blade::directive('money', function ($expression) {
            return "<?php echo '$' . number_format({$expression}, 0, ',', '.'); ?>";
        });
        
        View::composer('*', function ($view) {
            $view->with('user', Auth::user());
        });
    }

}
