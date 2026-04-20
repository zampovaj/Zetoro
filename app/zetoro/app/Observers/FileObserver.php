<?php

namespace App\Observers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileObserver
{
    public function deleting(File $file)
    {
        $folderName = dirname($file->path);

        if (Storage::disk('pdf_vault')->exists($folderName)) {
            Storage::disk('pdf_vault')->deleteDirectory($folderName);
        }
    }
}
