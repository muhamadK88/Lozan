<?php

namespace App\Models;

use App\Support\ArticleCache;
use App\Support\HtmlSanitizer;
use App\Support\Slug;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['admin_id', 'title', 'slug', 'cover_image', 'body', 'published_at'])]
class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Article $article): void {
            // Always normalise the slug: accept Kurdish, spaces or pasted
            // text and convert it to a safe URL segment. A blank slug falls
            // back to the title so the article can always be saved.
            $source = filled($article->slug)
                ? (string) $article->slug
                : (string) $article->title;

            $article->slug = Slug::make($source, $article->id);

            // Defence in depth: every write path is sanitised, even if a
            // future controller forgets to call the sanitiser explicitly.
            $article->body = app(HtmlSanitizer::class)->clean((string) $article->body);
        });

        static::saved(function (Article $article): void {
            ArticleCache::forgetHome();
            ArticleCache::forgetArticle($article->slug);

            if ($article->wasChanged('slug')) {
                ArticleCache::forgetArticle($article->getOriginal('slug'));
            }
        });

        static::deleted(function (Article $article): void {
            ArticleCache::forgetHome();
            ArticleCache::forgetArticle($article->slug);
        });
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Only articles whose publication time has arrived are public.
     */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function coverUrl(): Attribute
    {
        return Attribute::get(
            fn (): ?string => $this->cover_image
                ? '/storage/'.ltrim((string) $this->cover_image, '/')
                : null
        );
    }

    protected function excerpt(): Attribute
    {
        return Attribute::get(
            fn (): string => Str::limit(trim(strip_tags((string) $this->body)), 160)
        );
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->isPast();
    }
}
