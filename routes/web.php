<?php

use App\Http\Controllers\LocalHostRedirectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Local development helper
|--------------------------------------------------------------------------
|
| `php artisan serve` advertises http://127.0.0.1:8000, while the public
| site only answers on the configured PUBLIC_DOMAIN (localhost). Without
| this, opening the printed URL shows a 404. Production hosts are untouched.
|
*/
if (app()->environment('local')) {
    Route::domain('127.0.0.1')
        ->middleware('public')
        ->any('{path?}', LocalHostRedirectController::class)
        ->where('path', '.*');
}

/*
|--------------------------------------------------------------------------
| Public website: lozan.com
|--------------------------------------------------------------------------
|
| Stateless group: no authentication, no session, no admin routes.
| Every route in routes/public.php is only reachable through this host.
|
*/
Route::domain(config('app.public_domain'))
    ->middleware('public')
    ->group(base_path('routes/public.php'));

/*
|--------------------------------------------------------------------------
| Admin panel: admin.lozan.com
|--------------------------------------------------------------------------
|
| Completely separate host with its own session/CSRF stack. The "admin."
| name prefix means every admin URL is generated against the admin host.
| These routes are never registered on the public domain.
|
*/
Route::domain(config('app.admin_domain'))
    ->middleware('web')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));
