<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

trait ImageRemoveTrait
{
    public function deleteImages($model, $folder, $imageColumn)
    {
        // Delete the cover image if it exists
        $coverImagePath = public_path('backend/uploads/' . $folder . '/' . $model->$imageColumn);
        if ($model->$imageColumn !== null && File::exists($coverImagePath)) {
            unlink($coverImagePath);
           $model->update([$imageColumn => null]);
        }

        // Delete all media images if they exist
        if (method_exists($model, 'media') && $model->media()->count() > 0) {
            foreach ($model->media as $media) {
                $mediaImagePath = public_path('backend/uploads/' . $folder . '/' . $media->file_name);
                if (File::exists($mediaImagePath)) {
                    unlink($mediaImagePath);
                }
                $media->delete();
            }
        }
    }

    public function removeImage($model, $directory, $imageColumn)
    {
        $imagePath = public_path('backend/uploads/' . $directory . '/' . $model->$imageColumn);
        if (!empty($model->$imageColumn) && File::exists($imagePath)) {
            unlink($imagePath);
            // Update the model's column and save
            $model->update([$imageColumn => null]);
        }
        if ( !empty($model->$imageColumn) ){
            $model->update([$imageColumn => null]);
        }

    }
}

