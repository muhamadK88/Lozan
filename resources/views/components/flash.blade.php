@if (session('status'))
    <div class="rounded-2xl border border-brand-200 bg-brand-50 px-5 py-4 text-sm font-medium text-brand-800 dark:border-brand-800 dark:bg-brand-950/60 dark:text-brand-200" role="status">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-200" role="alert">
        <p class="mb-2 font-bold">تکایە ئەم خاڵانە چاک بکە:</p>
        <ul class="list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
