<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Support\ArticleCache;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $html = Cache::remember(
            ArticleCache::homeKey(),
            ArticleCache::ttl(),
            function (): string {
                $articles = Article::query()
                    ->select(['id', 'admin_id', 'title', 'slug', 'cover_image', 'body', 'published_at'])
                    ->with('admin:id,name')
                    ->published()
                    ->latest('published_at')
                    ->limit(12)
                    ->get();

                return view('public.home', [
                    'articles' => $articles,
                ])->render();
            }
        );

        return response($html)
            ->header('Cache-Control', 'public, max-age='.ArticleCache::ttl().', stale-while-revalidate=60');
    }
}
