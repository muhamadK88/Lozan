@extends('layouts.app')

@section('title', 'سەرەتا | پەیمانگەی لۆزان')
@section('description', 'دوایین هەواڵ و چالاکییەکانی پەیمانگەی لۆزان.')

@section('content')
    <section class="relative overflow-hidden border-b border-stone-200 bg-gradient-to-br from-brand-800 via-brand-700 to-emerald-900 dark:border-stone-800">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, white 1px, transparent 1px), radial-gradient(circle at 80% 60%, white 1px, transparent 1px); background-size: 42px 42px;"></div>

        <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <div class="max-w-3xl space-y-6 text-white">
                <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-1.5 text-xs font-semibold tracking-wide backdrop-blur">
                    ماڵپەڕی فەرمی
                </span>
                <h1 class="text-3xl font-extrabold leading-[1.5] sm:text-5xl sm:leading-[1.4]">
                    زانست و ڕۆشنبیری بۆ
                    <span class="text-brand-200">پێشەنگی کوردستان</span>
                </h1>
                <p class="max-w-2xl text-base leading-8 text-brand-50/90 sm:text-lg">
                    پەیمانگەی لۆزان بەردەوامە لە بەرەوپێشبردنی توێژینەوە، فێرکاری و چالاکییە ڕۆشنبیرییەکان. لێرە دوایین هەواڵ و ڕاپۆرتەکان بەدەست دەهێنیت.
                </p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="#latest" class="inline-flex items-center rounded-xl bg-white px-6 py-3 text-sm font-bold text-brand-800 shadow-lg transition hover:bg-brand-50">
                        خوێندنەوەی هەواڵەکان
                    </a>
                    <a href="#footer" class="inline-flex items-center rounded-xl border border-white/30 bg-white/10 px-6 py-3 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20">
                        پەیوەندیکردن
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="latest" class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="mb-1 text-sm font-semibold text-brand-700 dark:text-brand-300">دوایین بڵاوکراوەکان</p>
                <h2 class="text-2xl font-extrabold tracking-tight sm:text-3xl">هەواڵ و چالاکییەکان</h2>
            </div>
            <time datetime="{{ now()->toAtomString() }}" class="text-xs font-medium text-stone-500 dark:text-stone-400">
                نوێکراوەتەوە: {{ now()->format('Y/m/d') }}
            </time>
        </div>

        @if ($articles->isEmpty())
            <div class="rounded-3xl border border-dashed border-stone-300 bg-white p-12 text-center dark:border-stone-700 dark:bg-stone-900">
                <p class="text-lg font-bold">هێشتا هیچ وتارێک بڵاونەکراوەتەوە.</p>
                <p class="mt-2 text-sm text-stone-500 dark:text-stone-400">تکایە دواتر سەردان بکەوە.</p>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $article)
                    <x-article-card :article="$article" />
                @endforeach
            </div>
        @endif
    </section>
@endsection
