<?php

namespace App\DTOs;

class CreateArticleRequest
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $url,
        public string $pdfPath,
        public string $fileName,
    ) {}
}
