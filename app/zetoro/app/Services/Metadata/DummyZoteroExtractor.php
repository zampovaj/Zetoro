<?php

namespace App\Services\Metadata;

use App\Contracts\IMetadataExtractor;
use App\DTOs\ArticleMetadataDto;

class DummyZoteroExtractor implements IMetadataExtractor
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function extractArticleMetadataDto(string $url): ArticleMetadataDto
    {
        return new ArticleMetadataDto(
            title: 'Dual EC: A Standardized Back Door',
            doi: '10.1007/978-3-662-49301-4_17',
            year: 2016,
            authors: ['Daniel Bernstein', 'Tanja Lange'],
        );
    }
}
