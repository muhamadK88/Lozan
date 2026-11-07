<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_admin_login_page(): void
    {
        $this->get('http://admin.localhost/dashboard')
            ->assertRedirect('http://admin.localhost/login');
    }

    public function test_dashboard_renders_for_an_authenticated_admin(): void
    {
        $admin = Admin::factory()->create();
        Article::factory()->for($admin)->create();
        Article::factory()->for($admin)->draft()->create();

        $response = $this
            ->actingAs($admin, 'admin')
            ->get('http://admin.localhost/dashboard');

        $response->assertOk();
        $response->assertSee('داشبۆرد');
        $response->assertSee('هەموو وتارەکان');
    }

    public function test_article_management_pages_render(): void
    {
        $admin = Admin::factory()->create();
        $article = Article::factory()->for($admin)->create();

        $this->actingAs($admin, 'admin')
            ->get('http://admin.localhost/articles')
            ->assertOk()
            ->assertSee($article->title);

        $this->actingAs($admin, 'admin')
            ->get('http://admin.localhost/articles/create')
            ->assertOk()
            ->assertSee('ناوەڕۆکی وتار');

        $this->actingAs($admin, 'admin')
            ->get('http://admin.localhost/articles/'.$article->slug.'/edit')
            ->assertOk()
            ->assertSee($article->title);
    }

    public function test_upload_endpoint_requires_authentication(): void
    {
        $this->post('http://admin.localhost/uploads/image')
            ->assertRedirect('http://admin.localhost/login');
    }

    public function test_admin_panel_sets_no_store_and_noindex_headers(): void
    {
        $admin = Admin::factory()->create();

        $response = $this
            ->actingAs($admin, 'admin')
            ->get('http://admin.localhost/dashboard');

        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertStringContainsString('no-store', (string) $response->headers->get('Cache-Control'));
    }
}
