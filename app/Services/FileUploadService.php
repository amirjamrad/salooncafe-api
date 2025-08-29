<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;
class FileUploadService{
    public static function uploadImage(UploadedFile $file, string $model)
    {
        $folder = "images/{$model}";

        $imageName = uniqid() .'.'. $file->getClientOriginalExtension();

        $file->storeAs( $folder, $imageName , 'public');

        return $imageName;
    }

}
