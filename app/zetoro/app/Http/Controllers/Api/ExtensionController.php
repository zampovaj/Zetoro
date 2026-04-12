<?php

namespace App\Http\Controllers\Api;

use App\DTOs\CreateArticleRequest;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessExtensionPayload;
use Illuminate\Http\Request;

class ExtensionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
            'pdfPath' => 'required|string|between:1,100',
            'fileName' => 'required|string|between:1,100',
        ]);

        ProcessExtensionPayload::dispatch(
            new CreateArticleRequest(
                url: $validated['url'],
                pdfPath: $validated['pdfPath'],
                fileName: $validated['fileName'],
            )
        );

        return response()->json([
            'message' => 'Paylload acceoetd and queued.',
        ], 202);
    }
}
