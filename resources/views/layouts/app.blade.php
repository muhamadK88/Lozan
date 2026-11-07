<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'پەیمانگەی لۆزان')</title>
    <meta name="description" content="@yield('description', 'ماڵپەڕی فەرمیی پەیمانگەی لۆزان بۆ زانست، ڕۆشنبیری و توێژینەوە.')">
    <meta name="theme-color" content="#047857">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <x-theme-script />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-stone-50 text-stone-900 antialiased dark:bg-stone-950 dark:text-stone-100">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:start-4 focus:z-50 focus:rounded-xl focus:bg-brand-700 focus:px-4 focus:py-2 focus:text-white">
        بازدان بۆ ناوەڕۆک
    </a>

    <header class="sticky top-0 z-40 border-b border-stone-200/80 bg-stone-50/90 backdrop-blur-md dark:border-stone-800 dark:bg-stone-950/90">
        <div class="mx-auto flex h-20 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="flex size-11 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-800 text-xl font-extrabold text-white shadow-lg shadow-brand-600/20">
                    ل
                </span>
                <span class="leading-tight">
                    <span class="block text-lg font-extrabold tracking-tight">پەیمانگەی لۆزان</span>
                    <span class="block text-xs font-medium text-stone-500 dark:text-stone-400">زانست · ڕۆشنبیری · توێژینەوە</span>
                </span>
            </a>

            <nav class="hidden items-center gap-1 md:flex" aria-label="سەرەکی">
                <a href="{{ route('home') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100 hover:text-brand-700 dark:text-stone-300 dark:hover:bg-stone-900 dark:hover:text-brand-300">
                    سەرەتا
                </a>
                <a href="#footer" class="rounded-xl px-4 py-2 text-sm font-semibold text-stone-700 transition hover:bg-stone-100 hover:text-brand-700 dark:text-stone-300 dark:hover:bg-stone-900 dark:hover:text-brand-300">
                    پەیوەندی
                </a>
            </nav>

            <div class="flex items-center gap-2">
                <x-theme-toggle />

                <button
                    type="button"
                    data-mobile-menu-toggle
                    aria-expanded="false"
                    aria-controls="mobile-menu"
                    class="inline-flex size-10 items-center justify-center rounded-full border border-stone-200 bg-white text-stone-600 md:hidden dark:border-stone-700 dark:bg-stone-900 dark:text-stone-300"
                >
                    <span class="sr-only">کردنەوەی لیست</span>
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" data-mobile-menu class="hidden border-t border-stone-200 bg-stone-50 px-4 py-3 md:hidden dark:border-stone-800 dark:bg-stone-950">
            <nav class="flex flex-col gap-1" aria-label="مۆبایل">
                <a href="{{ route('home') }}" class="rounded-xl px-3 py-2 text-sm font-semibold hover:bg-stone-100 dark:hover:bg-stone-900">سەرەتا</a>
                <a href="#footer" class="rounded-xl px-3 py-2 text-sm font-semibold hover:bg-stone-100 dark:hover:bg-stone-900">پەیوەندی</a>
            </nav>
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    <footer id="footer" class="mt-20 border-t border-stone-200 bg-white dark:border-stone-800 dark:bg-stone-900">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 py-12 sm:px-6 md:grid-cols-3 lg:px-8">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-800 font-extrabold text-white">ل</span>
                    <span class="font-extrabold">پەیمانگەی لۆزان</span>
                </div>
                <p class="text-sm leading-7 text-stone-600 dark:text-stone-400">
                    پەیمانگەیەکی سەربەخۆ بۆ پەرەپێدانی زانست، ڕۆشنبیری و توێژینەوەی زانستی لە کوردستان.
                </p>
            </div>

            <div>
                <h2 class="mb-3 text-sm font-bold">بەستەرە خێراکان</h2>
                <ul class="space-y-2 text-sm text-stone-600 dark:text-stone-400">
                    <li><a href="{{ route('home') }}" class="hover:text-brand-700 dark:hover:text-brand-300">سەرەتا</a></li>
                    <li><a href="#footer" class="hover:text-brand-700 dark:hover:text-brand-300">پەیوەندی</a></li>
                </ul>
            </div>

            <div>
                <h2 class="mb-3 text-sm font-bold">پەیوەندی</h2>
                <p class="text-sm leading-7 text-stone-600 dark:text-stone-400">
                    ئیمەیڵ: info@lozan.com<br>
                    هەولێر، کوردستان
                </p>
            </div>
        </div>

        <div class="border-t border-stone-200 py-5 text-center text-xs text-stone-500 dark:border-stone-800 dark:text-stone-400">
            © {{ date('Y') }} پەیمانگەی لۆزان. هەموو مافەکان پارێزراون.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
