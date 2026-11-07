<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $now = now();

        $stats = [
            'total' => Article::query()->count(),
            'published' => Article::query()
                ->whereNotNull('published_at')
                ->where('published_at', '<=', $now)
                ->count(),
            'scheduled' => Article::query()
                ->where('published_at', '>', $now)
                ->count(),
            'drafts' => Article::query()->whereNull('published_at')->count(),
        ];

        $recent = Article::query()
            ->with('admin:id,name')
            ->latest('id')
            ->limit(6)
            ->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recent' => $recent,
        ]);
    }
}
