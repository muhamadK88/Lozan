<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use App\Models\Article;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $articles = Article::query()
            ->with('admin:id,name')
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = '%'.str_replace(['%', '_'], ['\\%', '\\_'], (string) $request->input('q')).'%';
                $query->where('title', 'like', $search);
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.articles.index', [
            'articles' => $articles,
        ]);
    }

    public function create(): View
    {
        return view('admin.articles.create');
    }

    public function store(StoreArticleRequest $request, ImageService $images): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->storeCover($request, $images);
        }

        $data['admin_id'] = $request->user('admin')->id;

        Article::create($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('status', 'وتارەکە بە سەرکەوتوویی دروستکرا.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(UpdateArticleRequest $request, Article $article, ImageService $images): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $oldCover = $article->cover_image;
            $data['cover_image'] = $this->storeCover($request, $images);
            $images->delete($oldCover);
        }

        $article->update($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('status', 'وتارەکە بە سەرکەوتوویی نوێکرایەوە.');
    }

    public function destroy(Article $article, ImageService $images): RedirectResponse
    {
        $images->delete($article->cover_image);
        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('status', 'وتارەکە سڕایەوە.');
    }

    private function storeCover(Request $request, ImageService $images): string
    {
        try {
            return $images->storeAsWebp($request->file('cover_image'), 'articles/covers');
        } catch (RuntimeException $exception) {
            report($exception);

            throw ValidationException::withMessages([
                'cover_image' => 'وێنەکە نەتوانرا پرۆسێس بکرێت. تکایە وێنەیەکی تر هەڵبژێرە.',
            ]);
        }
    }
}
