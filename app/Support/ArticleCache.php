<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

final class ArticleCache
{
    private const VERSION = 'v1';

    public static function homeKey(): string
    {
        return 'public.home.'.self::VERSION;
    }

    public static function articleKey(string $slug): string
    {
        return 'public.article.'.self::VERSION.'.'.$slug;
    }

    public static function ttl(): int
    {
        return max(60, (int) config('lozan.cache_ttl', 600));
    }

    public static function forgetHome(): void
    {
        Cache::forget(self::homeKey());
    }

    public static function forgetArticle(?string $slug): void
    {
        if (filled($slug)) {
            Cache::forget(self::articleKey($slug));
        }
    }
}
