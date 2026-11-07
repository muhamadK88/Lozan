<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_an_article_and_cover_is_converted_to_webp(): void
    {
        Storage::fake('public');
        $admin = Admin::factory()->create();

        $response = $this
            ->actingAs($admin, 'admin')
            ->post('http://admin.localhost/articles', [
                'title' => 'وتاری نوێی پەیمانگە',
                'body' => '<p>ناوەڕۆکی وتار</p>',
                'published_at' => now()->format('Y-m-d\TH:i'),
                'cover_image' => UploadedFile::fake()->image('cover.png', 2000, 1200),
            ]);

        $response->assertRedirect('http://admin.localhost/articles');

        $article = Article::query()->firstOrFail();

        $this->assertSame($admin->id, $article->admin_id);
        $this->assertStringEndsWith('.webp', $article->cover_image);
        Storage::disk('public')->assertExists($article->cover_image);
    }

    public function test_rich_text_is_sanitised_against_xss(): void
    {
        $admin = Admin::factory()->create();

        $payload = '<p>سڵاو</p>'
            .'<script>alert(1)</script>'
            .'<img src="x" onerror="alert(1)">'
            .'<a href="javascript:alert(1)">بەستەر</a>';

        $this
            ->actingAs($admin, 'admin')
            ->post('http://admin.localhost/articles', [
                'title' => 'وتاری XSS',
                'body' => $payload,
            ])
            ->assertRedirect();

        $body = Article::query()->firstOrFail()->body;

        $this->assertStringContainsString('سڵاو', $body);
        $this->assertStringNotContainsString('<script', $body);
        $this->assertStringNotContainsString('onerror', $body);
        $this->assertStringNotContainsString('javascript:', $body);
    }

    public function test_youtube_embeds_are_allowed_but_other_iframes_are_removed(): void
    {
        $admin = Admin::factory()->create();

        $this
            ->actingAs($admin, 'admin')
            ->post('http://admin.localhost/articles', [
                'title' => 'وتاری ڤیدیۆ',
                'body' => '<p>ڤیدیۆ</p>'
                    .'<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" allowfullscreen></iframe>'
                    .'<iframe src="https://evil.example/embed"></iframe>',
            ])
            ->assertRedirect();

        $body = Article::query()->firstOrFail()->body;

        $this->assertStringContainsString('youtube.com/embed/dQw4w9WgXcQ', $body);
        $this->assertStringNotContainsString('evil.example', $body);
    }

    public function test_slug_is_generated_from_a_kurdish_title_and_is_unique(): void
    {
        $admin = Admin::factory()->create();

        $this
            ->actingAs($admin, 'admin')
            ->post('http://admin.localhost/articles', [
                'title' => 'پەیمانگەی لۆزان',
                'body' => '<p>یەک</p>',
            ])
            ->assertRedirect();

        $this
            ->actingAs($admin, 'admin')
            ->post('http://admin.localhost/articles', [
                'title' => 'پەیمانگەی لۆزان',
                'body' => '<p>دوو</p>',
            ])
            ->assertRedirect();

        $slugs = Article::query()->orderBy('id')->pluck('slug');

        $this->assertCount(2, $slugs);
        $this->assertNotSame($slugs[0], $slugs[1]);
        $this->assertMatchesRegularExpression('/^[a-z0-9-]+$/', $slugs[0]);
    }

    public function test_a_kurdish_or_messy_slug_is_normalised_instead_of_rejected(): void
    {
        $admin = Admin::factory()->create();

        $this
            ->actingAs($admin, 'admin')
            ->post('http://admin.localhost/articles', [
                'title' => 'هەواڵی نوێ',
                'slug' => 'هەواڵی نوێ / Lozan News!',
                'body' => '<p>ناوەڕۆکی وتار</p>',
                'published_at' => now()->format('Y-m-d\TH:i'),
            ])
            ->assertRedirect('http://admin.localhost/articles');

        $article = Article::query()->firstOrFail();

        $this->assertMatchesRegularExpression('/^[a-z0-9_-]+$/', $article->slug);
        $this->assertStringContainsString('lozan-news', $article->slug);
    }

    public function test_duplicate_slug_input_is_automatically_made_unique(): void
    {
        $admin = Admin::factory()->create();

        foreach (['یەک', 'دوو'] as $title) {
            $this
                ->actingAs($admin, 'admin')
                ->post('http://admin.localhost/articles', [
                    'title' => $title,
                    'slug' => 'lozan-news',
                    'body' => '<p>'.$title.'</p>',
                ])
                ->assertRedirect('http://admin.localhost/articles');
        }

        $this->assertSame(
            ['lozan-news', 'lozan-news-2'],
            Article::query()->orderBy('id')->pluck('slug')->all(),
        );
    }

    public function test_an_article_with_a_publication_date_is_visible_on_the_homepage(): void
    {
        $admin = Admin::factory()->create();

        $this
            ->actingAs($admin, 'admin')
            ->post('http://admin.localhost/articles', [
                'title' => 'وتاری بڵاوکراوەی نوێ',
                'body' => '<p>ناوەڕۆکی وتار</p>',
                'published_at' => now()->subMinute()->format('Y-m-d\TH:i'),
            ])
            ->assertRedirect('http://admin.localhost/articles');

        $this->assertTrue(Article::query()->firstOrFail()->isPublished());

        $this->get('http://localhost/')
            ->assertOk()
            ->assertSee('وتاری بڵاوکراوەی نوێ');
    }

    public function test_inline_image_upload_returns_a_webp_location(): void
    {
        Storage::fake('public');
        $admin = Admin::factory()->create();

        $response = $this
            ->actingAs($admin, 'admin')
            ->post('http://admin.localhost/uploads/image', [
                'image' => UploadedFile::fake()->image('inline.jpg', 1000, 700),
            ]);

        $response->assertOk();
        $response->assertJsonStructure(['location']);

        $location = $response->json('location');

        $this->assertStringStartsWith('/storage/articles/inline/', $location);
        $this->assertStringEndsWith('.webp', $location);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $location));
    }

    public function test_only_two_admin_accounts_can_exist(): void
    {
        Admin::factory()->create();
        Admin::factory()->create();

        $this->expectException(\RuntimeException::class);

        Admin::factory()->create();
    }
}
