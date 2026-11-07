<?php

namespace App\Support;

use App\Models\Article;
use Illuminate\Support\Str;

final class Slug
{
    /**
     * Kurdish (Sorani) is written in Arabic script. Str::slug() cannot
     * transliterate it by itself, so we convert the letters to a readable
     * Latin form before generating the final slug.
     *
     * @var array<string, string>
     */
    private const TRANSLITERATION = [
        'وو' => 'u',
        'ئ' => 'a', 'ا' => 'a', 'أ' => 'a', 'إ' => 'e', 'آ' => 'a',
        'ب' => 'b', 'پ' => 'p', 'ت' => 't', 'ث' => 's', 'ج' => 'j',
        'چ' => 'ch', 'ح' => 'h', 'خ' => 'kh', 'د' => 'd', 'ذ' => 'z',
        'ر' => 'r', 'ڕ' => 'r', 'ز' => 'z', 'ژ' => 'zh', 'س' => 's',
        'ش' => 'sh', 'ص' => 's', 'ض' => 'd', 'ط' => 't', 'ظ' => 'z',
        'ع' => '', 'غ' => 'gh', 'ف' => 'f', 'ڤ' => 'v', 'ق' => 'q',
        'ک' => 'k', 'ك' => 'k', 'گ' => 'g', 'ل' => 'l', 'ڵ' => 'l',
        'م' => 'm', 'ن' => 'n', 'و' => 'w', 'ۆ' => 'o', 'ھ' => 'h',
        'ه' => 'h', 'ە' => 'e', 'ی' => 'y', 'ي' => 'y', 'ێ' => 'e',
        'ء' => '', 'ة' => 'e', 'ى' => 'y', 'ۊ' => 'u', 'ۋ' => 'v',

        // Arabic-Indic and Persian digits.
        '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
        '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
        '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
    ];

    /**
     * Maximum slug length. SQL column is 200; leave room for a "-2" suffix.
     */
    private const MAX_LENGTH = 180;

    /**
     * Turn any human input (Kurdish title, pasted text, mixed case) into a
     * safe URL segment that only contains letters, numbers, "-" and "_".
     *
     * Instead of rejecting what the editor types, we normalise it, so the
     * article can always be saved.
     */
    public static function make(string $source, ?int $ignoreId = null): string
    {
        $value = strtr(Str::lower(trim($source)), self::TRANSLITERATION);

        // Remove Arabic diacritics.
        $value = (string) preg_replace('/[\x{064B}-\x{065F}\x{0670}]/u', '', $value);

        // Anything that is not a letter, number, dash or underscore becomes
        // a dash; then collapse repeats and trim the edges.
        $value = (string) preg_replace('/[^a-z0-9_]+/', '-', $value);
        $value = (string) preg_replace('/-+/', '-', $value);
        $value = trim($value, '-_');
        $value = substr($value, 0, self::MAX_LENGTH);

        $base = $value !== '' ? $value : 'article';
        $slug = $base;
        $suffix = 2;

        while (self::exists($slug, $ignoreId)) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private static function exists(string $slug, ?int $ignoreId): bool
    {
        return Article::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists();
    }
}
