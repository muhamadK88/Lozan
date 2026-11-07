<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user('admin') !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'body' => ['required', 'string', 'max:500000'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'ناونیشانی وتار پێویستە.',
            'title.max' => 'ناونیشان نابێت لە ٢٠٠ پیت زیاتر بێت.',
            'slug.max' => 'بەستەرەکە نابێت لە ٢٠٠ پیت زیاتر بێت.',
            'cover_image.image' => 'فایلی بەرگی وتار دەبێت وێنە بێت.',
            'cover_image.mimes' => 'تەنها وێنەی JPG، PNG یان WebP ڕێگەپێدراوە.',
            'cover_image.max' => 'قەبارەی وێنە نابێت لە ٥ مێگابایت زیاتر بێت.',
            'body.required' => 'ناوەڕۆکی وتار پێویستە.',
            'published_at.date' => 'ڕێکەوتی بڵاوکردنەوە دروست نییە.',
        ];
    }
}
