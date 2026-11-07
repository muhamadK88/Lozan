@extends('layouts.admin')

@section('title', 'وتارەکان | پەیمانگەی لۆزان')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight">وتارەکان</h1>
            <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">
                {{ number_format($articles->total()) }} وتار بەردەستە.
            </p>
        </div>

        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-brand-700/20 transition hover:bg-brand-800">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            وتاری نوێ
        </a>
    </div>

    <div class="rounded-3xl border border-stone-200 bg-white shadow-sm dark:border-stone-800 dark:bg-stone-900">
        <div class="border-b border-stone-200 p-4 dark:border-stone-800">
            <form method="GET" action="{{ route('admin.articles.index') }}" class="flex flex-wrap gap-2">
                <label for="q" class="sr-only">گەڕان</label>
                <input
                    id="q"
                    name="q"
                    type="search"
                    value="{{ request('q') }}"
                    placeholder="گەڕان بە ناونیشان..."
                    class="min-w-0 flex-1 rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 dark:border-stone-700 dark:bg-stone-950 dark:text-stone-100"
                >
                <button type="submit" class="rounded-xl border border-stone-300 bg-stone-50 px-4 py-2.5 text-sm font-bold text-stone-700 transition hover:bg-stone-100 dark:border-stone-700 dark:bg-stone-800 dark:text-stone-200">گەڕان</button>
                @if (request()->filled('q'))
                    <a href="{{ route('admin.articles.index') }}" class="rounded-xl px-4 py-2.5 text-sm font-bold text-stone-500 hover:text-stone-800 dark:text-stone-400 dark:hover:text-stone-200">سڕینەوەی گەڕان</a>
                @endif
            </form>
        </div>

        @if ($articles->isEmpty())
            <div class="px-5 py-16 text-center">
                <p class="text-lg font-bold">هیچ وتارێک نەدۆزرایەوە.</p>
                <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">وتارێکی نوێ دروست بکە یان گەڕانەکە بگۆڕە.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[820px] text-sm">
                    <thead class="text-xs text-stone-500 dark:text-stone-400">
                        <tr class="border-b border-stone-200 dark:border-stone-800">
                            <th class="px-5 py-3 text-start font-bold">وتار</th>
                            <th class="px-5 py-3 text-start font-bold">دۆخ</th>
                            <th class="px-5 py-3 text-start font-bold">بڵاوکردنەوە</th>
                            <th class="px-5 py-3 text-start font-bold">نوێکردنەوە</th>
                            <th class="px-5 py-3 text-end font-bold">کردارەکان</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach ($articles as $article)
                            <tr class="align-middle">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="size-12 shrink-0 overflow-hidden rounded-xl bg-stone-100 dark:bg-stone-800">
                                            @if ($article->cover_url)
                                                <img src="{{ $article->cover_url }}" alt="" loading="lazy" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full items-center justify-center text-sm font-bold text-stone-400">ل</div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-bold">{{ $article->title }}</p>
                                            <p class="mt-0.5 truncate text-xs text-stone-500 dark:text-stone-400" dir="ltr">{{ $article->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3"><x-article-status :article="$article" /></td>
                                <td class="px-5 py-3 text-xs text-stone-500 dark:text-stone-400">
                                    {{ $article->published_at?->format('Y/m/d H:i') ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-xs text-stone-500 dark:text-stone-400">
                                    {{ $article->updated_at?->format('Y/m/d H:i') }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($article->isPublished())
                                            <a href="{{ route('articles.show', $article->slug) }}" target="_blank" rel="noopener" class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-stone-500 hover:bg-stone-100 hover:text-stone-800 dark:text-stone-400 dark:hover:bg-stone-800 dark:hover:text-stone-200">بینین</a>
                                        @endif

                                        <a href="{{ route('admin.articles.edit', $article) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-brand-700 hover:bg-brand-50 dark:text-brand-300 dark:hover:bg-brand-950/60">دەستکاری</a>

                                        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('دڵنیایی لە سڕینەوەی ئەم وتارە؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg px-2.5 py-1.5 text-xs font-bold text-red-600 hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-950/50">سڕینەوە</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($articles->hasPages())
                <div class="border-t border-stone-200 px-5 py-4 dark:border-stone-800">
                    {{ $articles->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
