<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class DocumentRepository
{

    public function uploadImage($model, $request)
    {
        $existingImage = $model->image()->first();
        
        if ($request->hasFile('image')) {
            // Define the path where the image should be stored (public/images/users)
            $storagePath = 'images/users';
            
            // Move the uploaded file to the public/images/users directory
            $imageName = $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->move(public_path($storagePath), $imageName);
            
            // The URL to store in the database will be relative to the public directory
            $imageUrl = $storagePath . '/' . $imageName;

            // If an existing image is found, delete it and update the image details
            if ($existingImage) {
                $oldImagePath = public_path($existingImage->url);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $existingImage->update(['url' => $imageUrl, 'name' => $imageName]);
            } else {
                // Otherwise, save the new image
                $image = new Image([
                    'url' => $imageUrl,
                    'name' => $imageName,
                    'default' => 1,
                ]);
                $model->images()->save($image);
            }
        }

        return true;
    }

}

