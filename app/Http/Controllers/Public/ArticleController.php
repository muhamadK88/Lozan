<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Support\ArticleCache;
use App\Support\HtmlSanitizer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    public function __invoke(string $slug, HtmlSanitizer $sanitizer): Response
    {
        $html = Cache::remember(
            ArticleCache::articleKey($slug),
            ArticleCache::ttl(),
            function () use ($slug, $sanitizer): string {
                $article = Article::query()
                    ->with('admin:id,name')
                    ->published()
                    ->where('slug', $slug)
                    ->firstOrFail();

                // Defence in depth: even rows written outside the model
                // (imports, manual SQL) are sanitised before rendering.
                $article->body = $sanitizer->clean((string) $article->body);

                return view('public.article', [
                    'article' => $article,
                ])->render();
            }
        );

        return response($html)
            ->header('Cache-Control', 'public, max-age='.ArticleCache::ttl().', stale-while-revalidate=60');
    }
}
