<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Local-development convenience only (see routes/web.php).
 *
 * `php artisan serve` prints http://127.0.0.1:8000, but the public site is
 * bound to the `localhost` host. This sends browser hits on 127.0.0.1 to the
 * canonical local host so the printed URL never returns a confusing 404.
 */
class LocalHostRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $port = $request->getPort();
        $suffix = in_array($port, [80, 443], true) ? '' : ':'.$port;

        return redirect()->away(sprintf(
            '%s://%s%s/%s',
            $request->getScheme(),
            config('app.public_domain'),
            $suffix,
            ltrim($request->path(), '/'),
        ));
    }
}
