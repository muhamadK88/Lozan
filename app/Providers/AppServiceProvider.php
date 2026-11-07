<?php

namespace App\Providers;

use App\Support\HtmlSanitizer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HtmlSanitizer::class);
    }

    public function boot(): void
    {
        if ($this->app->isProduction() && str_starts_with((string) config('app.url'), 'https://')) {
            // Both hosts must always generate HTTPS URLs, even when TLS is
            // terminated by a reverse proxy in front of PHP-FPM.
            URL::forceScheme('https');
        }

        // Public pages are cached as full HTML. Absolute asset URLs would bake
        // the request origin (host and port) into that cache, so a page warmed
        // on one port would load its CSS from a dead origin on another.
        // Root-relative URLs keep cached HTML valid on every host.
        Vite::createAssetPathsUsing(
            fn (string $path): string => '/'.ltrim($path, '/')
        );

        $this->configureRateLimiting();
        $this->configureEloquentSafety();
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('admin-login', function (Request $request) {
            $email = Str::lower(trim((string) $request->input('email')));

            return [
                // Slow down targeted password guessing.
                Limit::perMinute(5)->by($email.'|'.$request->ip()),
                // Stop distributed guessing from a single IP.
                Limit::perMinute(20)->by($request->ip()),
            ];
        });
    }

    private function configureEloquentSafety(): void
    {
        if (app()->isProduction()) {
            return;
        }

        // Surface N+1 queries and misspelled mass-assignment keys during
        // development instead of silently degrading performance.
        Model::preventLazyLoading();
        Model::preventSilentlyDiscardingAttributes();
    }
}
