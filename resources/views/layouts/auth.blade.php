<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'چوونەژوورەوە | پەیمانگەی لۆزان')</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#047857">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <x-theme-script />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col items-center justify-center bg-stone-100 px-4 py-10 text-stone-900 dark:bg-stone-950 dark:text-stone-100">
    <div class="absolute top-5 end-5">
        <x-theme-toggle />
    </div>

    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <span class="mx-auto mb-4 flex size-14 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-500 to-brand-800 text-2xl font-extrabold text-white shadow-lg shadow-brand-600/20">
                ل
            </span>
            <h1 class="text-xl font-extrabold">پەیمانگەی لۆزان</h1>
            <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">پانێڵی بەڕێوەبردن</p>
        </div>

        <div class="space-y-5">
            <x-flash />

            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-xl shadow-stone-200/50 sm:p-8 dark:border-stone-800 dark:bg-stone-900 dark:shadow-none">
                @yield('content')
            </div>
        </div>

        <p class="mt-6 text-center text-xs leading-6 text-stone-500 dark:text-stone-400">
            ئەم پانێڵە تەنها بۆ دوو بەڕێوەبەری پەیمانگە ڕێگەپێدراوە.
        </p>
    </div>
</body>
</html>
