<?php

use App\Http\Controllers\Api\ExtensionController;
use Illuminate\Support\Facades\Route;

Route::post('/extension', [ExtensionController::class, 'store']);
