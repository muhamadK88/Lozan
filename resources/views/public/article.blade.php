@extends('layouts.app')

@section('title', $article->title.' | پەیمانگەی لۆزان')
@section('description', $article->excerpt)

@section('content')
    <article class="mx-auto max-w-4xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">
        <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-2 text-sm font-semibold text-brand-700 transition hover:text-brand-800 dark:text-brand-300 dark:hover:text-brand-200">
            <svg class="size-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
            گەڕانەوە بۆ سەرەتا
        </a>

        <header class="space-y-5 border-b border-stone-200 pb-8 dark:border-stone-800">
            <h1 class="text-3xl font-extrabold leading-[1.6] sm:text-4xl sm:leading-[1.5]">
                {{ $article->title }}
            </h1>

            <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-stone-500 dark:text-stone-400">
                <span class="inline-flex items-center gap-2">
                    <span class="inline-flex size-8 items-center justify-center rounded-full bg-brand-100 text-xs font-bold text-brand-800 dark:bg-brand-900 dark:text-brand-200">
                        {{ mb_substr($article->admin->name, 0, 1) }}
                    </span>
                    {{ $article->admin->name }}
                </span>
                <time datetime="{{ $article->published_at?->toAtomString() }}">
                    {{ $article->published_at?->format('Y/m/d') }}
                </time>
            </div>
        </header>

        @if ($article->cover_url)
            <figure class="mt-8 overflow-hidden rounded-3xl border border-stone-200 bg-stone-100 dark:border-stone-800 dark:bg-stone-800">
                <div class="aspect-[16/9]">
                    <img
                        src="{{ $article->cover_url }}"
                        alt="{{ $article->title }}"
                        loading="eager"
                        fetchpriority="high"
                        decoding="async"
                        class="h-full w-full object-cover"
                    >
                </div>
            </figure>
        @endif

        <div class="article-body mt-10">
            {!! $article->body !!}
        </div>

        <footer class="mt-14 border-t border-stone-200 pt-6 dark:border-stone-800">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-700 transition hover:text-brand-800 dark:text-brand-300 dark:hover:text-brand-200">
                <svg class="size-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
                گەڕانەوە بۆ لیستی هەواڵەکان
            </a>
        </footer>
    </article>
@endsection
