<?php

namespace App\Models;

use App\Support\ArticleCache;
use Database\Factories\AdminFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class Admin extends Authenticatable
{
    /** @use HasFactory<AdminFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Admin $admin): void {
            $admin->email = mb_strtolower(trim((string) $admin->email));
        });

        static::creating(function (Admin $admin): void {
            $limit = (int) config('lozan.admin_limit', 2);

            if ($limit > 0 && static::query()->count() >= $limit) {
                throw new \RuntimeException(
                    "The institute is limited to {$limit} administrator accounts."
                );
            }
        });

        // The author name is printed on cached public pages. When an admin
        // renames themselves, drop those pages so the new name appears.
        static::saved(function (Admin $admin): void {
            if (! $admin->wasChanged('name')) {
                return;
            }

            foreach ($admin->articles()->pluck('slug') as $slug) {
                ArticleCache::forgetArticle((string) $slug);
            }

            ArticleCache::forgetHome();
        });
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
