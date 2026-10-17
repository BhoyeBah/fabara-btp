<?php

namespace App\Providers;

use App\Models\Message;
use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // En production, toutes les adresses générées sont en HTTPS (données chiffrées en transit).
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // Règle de mot de passe commune à l'inscription et au changement de mot de passe.
        Password::defaults(fn () => Password::min(8)->letters()->numbers());

        Paginator::defaultView('pagination.fabara');
        Paginator::defaultSimpleView('pagination.fabara');

        // Anti-abus : formulaires publics limités par adresse IP.
        RateLimiter::for('forms', fn (Request $request) => Limit::perMinute(6)->by($request->ip()));

        View::composer('*', function ($view) {
            static $site = null;
            $site ??= Setting::allValues();
            $view->with('site', $site);
        });

        View::composer('layouts.admin', function ($view) {
            $view->with('unreadCount', Message::where('from_admin', false)->whereNull('read_at')->count());
        });
    }
}
