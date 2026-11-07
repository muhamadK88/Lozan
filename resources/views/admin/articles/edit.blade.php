@extends('layouts.admin')

@section('title', 'دەستکاری وتار | پەیمانگەی لۆزان')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight">دەستکاری وتار</h1>
            <p class="mt-1 text-sm text-stone-500 dark:text-stone-400" dir="ltr">{{ $article->slug }}</p>
        </div>

        @if ($article->isPublished())
            <a href="{{ route('articles.show', $article->slug) }}" target="_blank" rel="noopener" class="rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm font-bold text-stone-600 transition hover:bg-stone-100 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-300 dark:hover:bg-stone-800">
                بینینی وتار
            </a>
        @endif
    </div>

    @include('admin.articles._form', ['article' => $article])
@endsection
