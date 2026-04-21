<?php

use App\Http\Controllers\FileDownloadController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'app')->name('app');
Route::get('/file/{file}/pdf', [FileDownloadController::class, 'show'])->name('files.pdf');