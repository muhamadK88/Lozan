@php($currentAdmin = auth('admin')->user())

<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'پانێڵی بەڕێوەبردن | پەیمانگەی لۆزان')</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#047857">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <x-theme-script />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-stone-100 text-stone-900 antialiased dark:bg-stone-950 dark:text-stone-100">
    <header class="sticky top-0 z-40 border-b border-stone-200 bg-white/90 backdrop-blur-md dark:border-stone-800 dark:bg-stone-900/90">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <span class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-800 text-lg font-extrabold text-white">ل</span>
                <div class="leading-tight">
                    <p class="text-sm font-extrabold">پانێڵی بەڕێوەبردن</p>
                    <p class="text-xs text-stone-500 dark:text-stone-400">{{ $currentAdmin?->name }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="hidden rounded-xl border border-stone-200 bg-white px-3 py-2 text-xs font-bold text-stone-600 transition hover:bg-stone-100 sm:inline-flex dark:border-stone-700 dark:bg-stone-900 dark:text-stone-300 dark:hover:bg-stone-800">
                    بینینی ماڵپەڕ
                </a>

                <x-theme-toggle />

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 transition hover:bg-red-100 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200 dark:hover:bg-red-950">
                        چوونەدەرەوە
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="mx-auto flex max-w-7xl gap-6 px-4 py-6 sm:px-6 lg:px-8">
        <aside class="hidden w-60 shrink-0 lg:block">
            <nav class="sticky top-24 space-y-1" aria-label="پانێڵ">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-brand-700 text-white shadow-lg shadow-brand-700/20' : 'text-stone-600 hover:bg-white hover:text-brand-700 dark:text-stone-300 dark:hover:bg-stone-900 dark:hover:text-brand-300' }}">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25Zm9.75-9.75A2.25 2.25 0 0 1 15.75 3.75H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    داشبۆرد
                </a>

                <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition {{ request()->routeIs('admin.articles.*') ? 'bg-brand-700 text-white shadow-lg shadow-brand-700/20' : 'text-stone-600 hover:bg-white hover:text-brand-700 dark:text-stone-300 dark:hover:bg-stone-900 dark:hover:text-brand-300' }}">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25m4.5 18.75h-6a2.25 2.25 0 0 1-2.25-2.25V6.75A2.25 2.25 0 0 1 6.75 4.5h3.879a2.25 2.25 0 0 1 1.591.659l3.621 3.621a2.25 2.25 0 0 1 .659 1.591v8.629a2.25 2.25 0 0 1-2.25 2.25Z" />
                    </svg>
                    وتارەکان
                </a>

                <a href="{{ route('admin.articles.create') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-stone-600 transition hover:bg-white hover:text-brand-700 dark:text-stone-300 dark:hover:bg-stone-900 dark:hover:text-brand-300">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    وتاری نوێ
                </a>
            </nav>
        </aside>

        <main class="min-w-0 flex-1">
            <nav class="mb-4 flex gap-2 overflow-x-auto lg:hidden" aria-label="پانێڵی مۆبایل">
                <a href="{{ route('admin.dashboard') }}" class="shrink-0 rounded-xl px-3 py-2 text-xs font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-brand-700 text-white' : 'bg-white text-stone-600 dark:bg-stone-900 dark:text-stone-300' }}">داشبۆرد</a>
                <a href="{{ route('admin.articles.index') }}" class="shrink-0 rounded-xl px-3 py-2 text-xs font-bold {{ request()->routeIs('admin.articles.*') ? 'bg-brand-700 text-white' : 'bg-white text-stone-600 dark:bg-stone-900 dark:text-stone-300' }}">وتارەکان</a>
                <a href="{{ route('admin.articles.create') }}" class="shrink-0 rounded-xl bg-white px-3 py-2 text-xs font-bold text-stone-600 dark:bg-stone-900 dark:text-stone-300">وتاری نوێ</a>
            </nav>

            <div class="space-y-5">
                <x-flash />
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
