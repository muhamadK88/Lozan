<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class UploadController extends Controller
{
    public function image(Request $request, ImageService $images): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        try {
            $path = $images->storeAsWebp($request->file('image'), 'articles/inline');
        } catch (RuntimeException $exception) {
            report($exception);

            return response()->json([
                'message' => 'وێنەکە نەتوانرا پرۆسێس بکرێت.',
            ], 422);
        }

        return response()->json([
            'location' => '/storage/'.ltrim($path, '/'),
        ]);
    }
}
