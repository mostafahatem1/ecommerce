<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait ImageUploadTrait
{
    public function uploadSingleImage($image, $name, $folder)
    {
        $file_name = Str::slug($name) . '.' . $image->getClientOriginalExtension();
        $path = public_path('/backend/uploads/' . $folder . '/' . $file_name);
        // Load the uploaded image from the temporary path using Intervention Image
        Image::make($image->getRealPath())
            // Resize the image to 500px width
            // Height is set to null so it will be automatically calculated to maintain the aspect ratio
            ->resize(500, null, function ($constraint) {
                // Keep the original aspect ratio (no stretching)
                $constraint->aspectRatio();
            })
            // Save the resized image to the given path with 100% quality
            ->save($path, 100);

        return $file_name;
    }

    // Upload multiple images
    public function uploadMultiImage($images, $name, $folder, $model, $startIndex = 1)
    {
        $i = $startIndex;
        foreach ($images as $image) {
            $file_name = Str::slug($name) . '_' . time() . '_' . $i . '.' . $image->getClientOriginalExtension();
            $file_size = $image->getSize();
            $file_type = $image->getMimeType();
            $path = public_path('/backend/uploads/' . $folder . '/' . $file_name);

            Image::make($image->getRealPath())
                ->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($path, 100);

            $model->media()->create([
                'file_name' => $file_name,
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_status' => true,
                'file_sort' => $i,
            ]);

            $i++;
        }
    }
}
