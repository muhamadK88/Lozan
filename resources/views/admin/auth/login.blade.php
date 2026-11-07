@extends('layouts.auth')

@section('title', 'چوونەژوورەوە | پەیمانگەی لۆزان')

@section('content')
    <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-bold">ئیمەیڵ</label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                dir="ltr"
                class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-left text-sm outline-none transition placeholder:text-stone-400 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 dark:border-stone-700 dark:bg-stone-950 dark:text-stone-100"
                placeholder="admin@lozan.com"
            >
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-bold">وشەی نهێنی</label>
            <input
                id="password"
                name="password"
                type="password"
                required
                autocomplete="current-password"
                dir="ltr"
                class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-left text-sm outline-none transition placeholder:text-stone-400 focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 dark:border-stone-700 dark:bg-stone-950 dark:text-stone-100"
                placeholder="••••••••"
            >
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-brand-700 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-brand-700/20 transition hover:bg-brand-800 focus:outline-none focus-visible:ring-4 focus-visible:ring-brand-500/30"
        >
            چوونەژوورەوە
        </button>

        <p class="text-center text-xs leading-6 text-stone-500 dark:text-stone-400">
            هەوڵەکان سنووردارن بۆ پاراستن لە هەڵوەشاندنەوەی وشەی نهێنی.
        </p>
    </form>
@endsection
