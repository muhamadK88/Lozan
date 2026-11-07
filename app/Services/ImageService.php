<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

final class ImageService
{
    /**
     * Re-encode an uploaded image as WebP, resizing it down when it is wider
     * than the configured maximum. Returns the path relative to the public
     * disk, for example: articles/covers/8c1....webp
     */
    public function storeAsWebp(UploadedFile $file, string $folder = 'articles'): string
    {
        $realPath = $file->getRealPath();

        if ($realPath === false) {
            throw new RuntimeException('The uploaded image could not be read.');
        }

        $info = @getimagesize($realPath);

        if ($info === false) {
            throw new RuntimeException('The uploaded file is not a valid image.');
        }

        [$width, $height] = $info;

        if ($width < 1 || $height < 1 || ($width * $height) > 25_000_000) {
            throw new RuntimeException('The image dimensions are not supported.');
        }

        $source = @imagecreatefromstring((string) file_get_contents($realPath));

        if ($source === false) {
            throw new RuntimeException('The uploaded image could not be processed.');
        }

        $maxWidth = max(320, (int) config('lozan.image_max_width', 1600));
        $ratio = $width > $maxWidth ? $maxWidth / $width : 1;
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        if ($canvas === false) {
            imagedestroy($source);

            throw new RuntimeException('The image canvas could not be created.');
        }

        // Preserve transparency for PNG/WebP sources.
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
        imagecopyresampled(
            $canvas, $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $width, $height,
        );

        imagedestroy($source);

        if (! function_exists('imagewebp')) {
            imagedestroy($canvas);

            throw new RuntimeException('WebP encoding is not available in this PHP build.');
        }

        ob_start();
        imagewebp($canvas, null, max(40, min(95, (int) config('lozan.image_quality', 82))));
        $binary = (string) ob_get_clean();
        imagedestroy($canvas);

        if ($binary === '') {
            throw new RuntimeException('The image could not be encoded.');
        }

        $path = trim($folder, '/').'/'.Str::uuid().'.webp';

        if (! Storage::disk('public')->put($path, $binary)) {
            throw new RuntimeException('The image could not be stored.');
        }

        return $path;
    }

    public function delete(?string $path): void
    {
        if (filled($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
