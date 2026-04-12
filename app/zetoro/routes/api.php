<?php

use App\Http\Controllers\Api\ExtensionController;
use Illuminate\Support\Facades\Route;

Route::post('/store', [ExtensionController::class, 'store']);
