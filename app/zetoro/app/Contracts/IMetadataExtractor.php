<?php

namespace App\Contracts;

use App\DTOs\ArticleMetadataDto;

interface IMetadataExtractor
{
    public function extractArticleMetadataDto(string $url): ArticleMetadataDto;
}
