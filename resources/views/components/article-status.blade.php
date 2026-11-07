@props(['article'])

@if ($article->published_at === null)
    <span class="inline-flex items-center rounded-full bg-stone-100 px-2.5 py-1 text-xs font-bold text-stone-600 dark:bg-stone-800 dark:text-stone-300">
        ڕەشنووس
    </span>
@elseif ($article->published_at->isFuture())
    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700 dark:bg-amber-950/50 dark:text-amber-300">
        خشتەکراوە
    </span>
@else
    <span class="inline-flex items-center rounded-full bg-brand-100 px-2.5 py-1 text-xs font-bold text-brand-700 dark:bg-brand-950/60 dark:text-brand-300">
        بڵاوکراوەتەوە
    </span>
@endif
