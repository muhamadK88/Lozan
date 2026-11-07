@extends('layouts.admin')

@section('title', 'داشبۆرد | پەیمانگەی لۆزان')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight">داشبۆرد</h1>
            <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">کورتەیەک لە دۆخی ماڵپەڕ و وتارەکان.</p>
        </div>

        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-brand-700/20 transition hover:bg-brand-800">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            وتاری نوێ
        </a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $cards = [
                ['label' => 'هەموو وتارەکان', 'value' => $stats['total'], 'tone' => 'bg-stone-900 text-white dark:bg-white dark:text-stone-900'],
                ['label' => 'بڵاوکراوەتەوە', 'value' => $stats['published'], 'tone' => 'bg-brand-700 text-white'],
                ['label' => 'خشتەکراوە', 'value' => $stats['scheduled'], 'tone' => 'bg-amber-500 text-white'],
                ['label' => 'ڕەشنووس', 'value' => $stats['drafts'], 'tone' => 'bg-stone-200 text-stone-800 dark:bg-stone-800 dark:text-stone-100'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm dark:border-stone-800 dark:bg-stone-900">
                <div class="mb-4 inline-flex rounded-xl px-3 py-1.5 text-xs font-bold {{ $card['tone'] }}">
                    {{ $card['label'] }}
                </div>
                <p class="text-3xl font-extrabold">{{ number_format($card['value']) }}</p>
            </div>
        @endforeach
    </div>

    <div class="rounded-3xl border border-stone-200 bg-white shadow-sm dark:border-stone-800 dark:bg-stone-900">
        <div class="flex items-center justify-between border-b border-stone-200 px-5 py-4 dark:border-stone-800">
            <h2 class="font-extrabold">دوایین وتارەکان</h2>
            <a href="{{ route('admin.articles.index') }}" class="text-xs font-bold text-brand-700 hover:text-brand-800 dark:text-brand-300 dark:hover:text-brand-200">بینینی هەموو</a>
        </div>

        @if ($recent->isEmpty())
            <div class="px-5 py-12 text-center">
                <p class="font-bold">هێشتا هیچ وتارێک نەنووسراوە.</p>
                <a href="{{ route('admin.articles.create') }}" class="mt-3 inline-flex rounded-xl bg-brand-700 px-4 py-2 text-sm font-bold text-white">دروستکردنی یەکەم وتار</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-sm">
                    <thead class="text-start text-xs text-stone-500 dark:text-stone-400">
                        <tr class="border-b border-stone-200 dark:border-stone-800">
                            <th class="px-5 py-3 text-start font-bold">ناونیشان</th>
                            <th class="px-5 py-3 text-start font-bold">دۆخ</th>
                            <th class="px-5 py-3 text-start font-bold">ڕێکەوت</th>
                            <th class="px-5 py-3 text-start font-bold">بەڕێوەبەر</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 dark:divide-stone-800">
                        @foreach ($recent as $article)
                            <tr>
                                <td class="max-w-xs px-5 py-3">
                                    <p class="truncate font-bold">{{ $article->title }}</p>
                                    <p class="mt-0.5 truncate text-xs text-stone-500 dark:text-stone-400" dir="ltr">{{ $article->slug }}</p>
                                </td>
                                <td class="px-5 py-3"><x-article-status :article="$article" /></td>
                                <td class="px-5 py-3 text-xs text-stone-500 dark:text-stone-400">
                                    {{ $article->published_at?->format('Y/m/d H:i') ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-xs">{{ $article->admin->name }}</td>
                                <td class="px-5 py-3 text-end">
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="text-xs font-bold text-brand-700 hover:text-brand-800 dark:text-brand-300">دەستکاری</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
