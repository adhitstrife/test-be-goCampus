<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function uploadFile($file)
    {
        $fileName = 'article_photo_' . Carbon::now()->timestamp . '.' . $file->getClientOriginalExtension();

        $isStoringSuccess = $file->storeAs('public/article_images', $fileName);

        if ($isStoringSuccess) {
            return $fileName;
        } else {
            return False;
        }
    }

    public function deleteFile($path)
    {
        Storage::delete($path);
    }
}
