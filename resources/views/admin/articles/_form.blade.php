@php
    $article = $article ?? null;
    $isEdit = $article !== null;
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('admin.articles.update', $article) : route('admin.articles.store') }}"
    enctype="multipart/form-data"
    class="space-y-6"
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm sm:p-6 dark:border-stone-800 dark:bg-stone-900">
        <div class="grid gap-5 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <label for="title" class="mb-2 block text-sm font-bold">ناونیشانی وتار</label>
                <input
                    id="title"
                    name="title"
                    type="text"
                    value="{{ old('title', $article?->title) }}"
                    required
                    maxlength="200"
                    class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 dark:border-stone-700 dark:bg-stone-950 dark:text-stone-100"
                >
            </div>

            <div>
                <label for="slug" class="mb-2 block text-sm font-bold">بەستەری وتار</label>
                <input
                    id="slug"
                    name="slug"
                    type="text"
                    value="{{ old('slug', $article?->slug) }}"
                    maxlength="200"
                    dir="ltr"
                    class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-left text-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 dark:border-stone-700 dark:bg-stone-950 dark:text-stone-100"
                    placeholder="lozan-news"
                >
                <p class="mt-1.5 text-xs text-stone-500 dark:text-stone-400">
                    بەتاڵی بهێڵە بۆ دروستکردنی خۆکار لە ناونیشان. هەر پیتێک بنووسیت (کوردی یان ئینگلیزی) خۆکارانە دەگۆڕدرێت بۆ بەستەرێکی دروست.
                </p>
            </div>

            <div>
                <label for="published_at" class="mb-2 block text-sm font-bold">ڕێکەوتی بڵاوکردنەوە</label>
                <input
                    id="published_at"
                    name="published_at"
                    type="datetime-local"
                    value="{{ old('published_at', $article?->published_at?->format('Y-m-d\TH:i') ?? ($isEdit ? '' : now()->format('Y-m-d\TH:i'))) }}"
                    class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 dark:border-stone-700 dark:bg-stone-950 dark:text-stone-100"
                >
                <p class="mt-1.5 text-xs text-stone-500 dark:text-stone-400">
                    ئێستا ڕێکەوتەکە پێشوەخت دانراوە، بۆیە دوای پاشەکەوتکردن یەکسەر بڵاودەبێتەوە. بەتاڵی بهێڵە بۆ ڕەشنووس، یان ڕێکەوتی داهاتوو بۆ خشتەکردن.
                </p>
            </div>

            <div class="lg:col-span-2">
                <span class="mb-2 block text-sm font-bold">وێنەی بەرگ</span>
                <div class="flex flex-wrap items-center gap-4">
                    <div data-cover-preview class="size-24 shrink-0 overflow-hidden rounded-2xl border border-stone-200 bg-stone-100 dark:border-stone-700 dark:bg-stone-800">
                        @if ($article?->cover_url)
                            <img src="{{ $article->cover_url }}" alt="" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-xl font-bold text-stone-400">ل</div>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <input
                            type="file"
                            name="cover_image"
                            accept="image/jpeg,image/png,image/webp"
                            data-cover-input
                            class="block w-full max-w-sm cursor-pointer rounded-xl border border-stone-300 bg-white p-2 text-xs file:me-3 file:rounded-lg file:border-0 file:bg-stone-100 file:px-3 file:py-2 file:text-xs file:font-bold file:text-stone-700 hover:file:bg-stone-200 dark:border-stone-700 dark:bg-stone-950 dark:text-stone-300 dark:file:bg-stone-800 dark:file:text-stone-200"
                        >
                        <p class="mt-1.5 text-xs text-stone-500 dark:text-stone-400">JPG، PNG یان WebP — زۆرترین ٥ مێگابایت. خۆکارانە بۆ WebP دەگۆڕدرێت.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm sm:p-6 dark:border-stone-800 dark:bg-stone-900">
        <label for="body" class="mb-2 block text-sm font-bold">ناوەڕۆکی وتار</label>
        <p class="mb-3 text-xs text-stone-500 dark:text-stone-400">دەتوانیت وێنە باربکەیت و ڤیدیۆی یوتیوب تێکەیت. ناوەڕۆک پێش پاشەکەوتکردن دەپارێزرێت.</p>
        <textarea
            id="body"
            name="body"
            rows="18"
            class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 dark:border-stone-700 dark:bg-stone-950 dark:text-stone-100"
        >{{ old('body', $article?->body) }}</textarea>
    </div>

    <div class="flex flex-wrap items-center justify-end gap-3">
        <a href="{{ route('admin.articles.index') }}" class="rounded-xl border border-stone-300 bg-white px-5 py-2.5 text-sm font-bold text-stone-600 transition hover:bg-stone-100 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-300 dark:hover:bg-stone-800">
            پاشگەزبوونەوە
        </a>
        <button type="submit" class="rounded-xl bg-brand-700 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-brand-700/20 transition hover:bg-brand-800 focus:outline-none focus-visible:ring-4 focus-visible:ring-brand-500/30">
            {{ $isEdit ? 'پاشەکەوتکردنی گۆڕانکاری' : 'بڵاوکردنەوەی وتار' }}
        </button>
    </div>
</form>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const uploadUrl = @json(route('admin.uploads.image'));

            // TinyMCE edits a hidden iframe, not the textarea. Force the
            // current editor content back into the form right before submit
            // so a valid article is never silently blocked by the browser.
            document.querySelector('#body')?.closest('form')?.addEventListener('submit', function () {
                if (window.tinymce) {
                    window.tinymce.triggerSave();
                }
            });

            if (window.tinymce) {
                tinymce.init({
                    selector: '#body',
                    license_key: 'gpl',
                    height: 620,
                    directionality: 'rtl',
                    menubar: false,
                    promotion: false,
                    branding: false,
                    plugins: 'lists link image media table code autolink wordcount',
                    toolbar: 'undo redo | blocks | bold italic underline | alignright aligncenter alignleft | bullist numlist | link image media table | removeformat code',
                    content_style: "body{font-family:'Noto Kufi Arabic',Tahoma,sans-serif;font-size:16px;line-height:2;direction:rtl}img{max-width:100%;height:auto}iframe{max-width:100%}",
                    automatic_uploads: true,
                    media_live_embeds: true,
                    images_upload_handler: async function (blobInfo) {
                        const data = new FormData();
                        data.append('image', blobInfo.blob(), blobInfo.filename());

                        const response = await fetch(uploadUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: data,
                            credentials: 'same-origin',
                        });

                        const payload = await response.json().catch(function () {
                            return {};
                        });

                        if (!response.ok || !payload.location) {
                            throw new Error(payload.message || 'وێنەکە بارنەکرا.');
                        }

                        return payload.location;
                    },
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                        });
                    },
                });
            }

            const coverInput = document.querySelector('[data-cover-input]');
            const coverPreview = document.querySelector('[data-cover-preview]');

            if (coverInput && coverPreview) {
                coverInput.addEventListener('change', function () {
                    const file = coverInput.files?.[0];

                    if (!file) {
                        return;
                    }

                    const url = URL.createObjectURL(file);
                    coverPreview.innerHTML = '';

                    const image = document.createElement('img');
                    image.src = url;
                    image.alt = '';
                    image.className = 'h-full w-full object-cover';
                    coverPreview.appendChild(image);
                });
            }
        });
    </script>
@endpush
