<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_only_lists_published_articles(): void
    {
        $admin = Admin::factory()->create();

        Article::factory()->for($admin)->create([
            'title' => 'وتاری بڵاوکراوە',
            'published_at' => now()->subDay(),
        ]);

        Article::factory()->for($admin)->draft()->create([
            'title' => 'ڕەشنووسی نەبینراو',
        ]);

        Article::factory()->for($admin)->scheduled()->create([
            'title' => 'وتاری داهاتوو',
        ]);

        $response = $this->get('http://localhost/');

        $response->assertOk();
        $response->assertSee('وتاری بڵاوکراوە');
        $response->assertDontSee('ڕەشنووسی نەبینراو');
        $response->assertDontSee('وتاری داهاتوو');
        $response->assertHeader('Cache-Control');
    }

    public function test_single_article_page_is_publicly_available(): void
    {
        $admin = Admin::factory()->create();
        $article = Article::factory()->for($admin)->create([
            'title' => 'ناونیشانی تاقیکاری',
            'body' => '<p>ناوەڕۆکی تاقیکاری</p>',
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get('http://localhost/article/'.$article->slug);

        $response->assertOk();
        $response->assertSee('ناونیشانی تاقیکاری');
        $response->assertSee('ناوەڕۆکی تاقیکاری');
        $response->assertSee($admin->name);
    }

    public function test_unpublished_article_returns_not_found(): void
    {
        $admin = Admin::factory()->create();
        $article = Article::factory()->for($admin)->draft()->create();

        $this->get('http://localhost/article/'.$article->slug)->assertNotFound();
    }

    public function test_article_cache_is_invalidated_when_the_article_changes(): void
    {
        $admin = Admin::factory()->create();
        $article = Article::factory()->for($admin)->create([
            'title' => 'ناونیشانی کۆن',
            'published_at' => now()->subHour(),
        ]);

        $this->get('http://localhost/article/'.$article->slug)
            ->assertSee('ناونیشانی کۆن');

        $article->update(['title' => 'ناونیشانی نوێ']);

        $this->get('http://localhost/article/'.$article->slug)
            ->assertSee('ناونیشانی نوێ')
            ->assertDontSee('ناونیشانی کۆن');
    }

    public function test_homepage_cache_is_invalidated_when_an_article_changes(): void
    {
        $admin = Admin::factory()->create();
        $article = Article::factory()->for($admin)->create([
            'title' => 'هەواڵی کۆن',
            'published_at' => now()->subHour(),
        ]);

        $this->get('http://localhost/')->assertSee('هەواڵی کۆن');

        $article->update(['title' => 'هەواڵی نوێ']);

        $this->get('http://localhost/')
            ->assertSee('هەواڵی نوێ')
            ->assertDontSee('هەواڵی کۆن');
    }

    public function test_cached_page_assets_use_origin_relative_urls(): void
    {
        $admin = Admin::factory()->create();
        Article::factory()->for($admin)->create([
            'published_at' => now()->subHour(),
        ]);

        $response = $this->get('http://localhost/');

        $response->assertOk();
        // Cached HTML must not bake the request origin into asset URLs,
        // otherwise warming the cache on one port breaks another port/host.
        $response->assertSee('/build/assets/', false);
        $response->assertDontSee('http://localhost/build/assets', false);
    }

    public function test_article_page_is_not_available_on_the_admin_host(): void
    {
        $admin = Admin::factory()->create();
        $article = Article::factory()->for($admin)->create([
            'published_at' => now()->subHour(),
        ]);

        $this->get('http://admin.localhost/article/'.$article->slug)->assertNotFound();
    }
}
