<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


trait ImageStorageTrait
{
    public function storeImage($image, $folder, $imagePath=null)
    {

    	if(!is_null($imagePath) && !empty($imagePath)){
            $fileToDelete = public_path().'/'.$imagePath;
    		// $fileToDelete = storage_path('app/public/'.$imagePath);
    		// check filed exist
			if (file_exists($fileToDelete)) {
			    File::delete($fileToDelete);
			}
		}
        if(!empty($image)){
            $path = public_path().'/'.$folder;
        	// $path = storage_path($folder);
            if(!File::isDirectory($path)){
                File::makeDirectory($path, 0777, true, true);
            } 

            $file_name = $image->getClientOriginalName();
            // Generate a random name for the image
            $imageName = $file_name.'_'.Str::random(10) . '.' . $image->getClientOriginalExtension();
            // Store the image in the specified folder
            $path = $image->move($folder,$imageName);
            // $path = $image->storeAs($folder, $imageName, 'public');

            return $imageName;
        }
    }
}