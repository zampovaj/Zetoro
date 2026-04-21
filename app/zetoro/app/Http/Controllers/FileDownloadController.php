<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileDownloadController extends Controller
{
    public function show(File $file): StreamedResponse {
        if (!Storage::disk('pdf_vault')->exists($file->path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('pdf_vault')->response($file->path);
    }
}
