@props(['article'])

<article class="group overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl dark:border-stone-800 dark:bg-stone-900">
    <a href="{{ route('articles.show', $article->slug) }}" class="block">
        <div class="relative aspect-[16/10] overflow-hidden bg-stone-100 dark:bg-stone-800">
            @if ($article->cover_url)
                <img
                    src="{{ $article->cover_url }}"
                    alt="{{ $article->title }}"
                    loading="lazy"
                    decoding="async"
                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                >
            @else
                <div class="flex h-full items-center justify-center bg-gradient-to-br from-brand-500 to-brand-800 text-5xl font-bold text-white/80">
                    ل
                </div>
            @endif
        </div>

        <div class="space-y-3 p-5">
            <time datetime="{{ $article->published_at?->toAtomString() }}" class="block text-xs font-medium text-stone-500 dark:text-stone-400">
                {{ $article->published_at?->format('Y/m/d') }}
            </time>

            <h2 class="text-lg font-bold leading-8 text-stone-900 transition group-hover:text-brand-700 dark:text-stone-100 dark:group-hover:text-brand-300">
                {{ $article->title }}
            </h2>

            <p class="line-clamp-3 text-sm leading-7 text-stone-600 dark:text-stone-400">
                {{ $article->excerpt }}
            </p>

            <div class="flex items-center gap-2 border-t border-stone-100 pt-3 text-xs text-stone-500 dark:border-stone-800 dark:text-stone-400">
                <span class="inline-flex size-7 items-center justify-center rounded-full bg-brand-100 font-bold text-brand-800 dark:bg-brand-900 dark:text-brand-200">
                    {{ mb_substr($article->admin->name, 0, 1) }}
                </span>
                <span>{{ $article->admin->name }}</span>
            </div>
        </div>
    </a>
</article>
