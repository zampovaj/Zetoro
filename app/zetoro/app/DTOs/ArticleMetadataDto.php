<?php

namespace App\DTOs;

class ArticleMetadataDto
{
    public function __construct(
        public ?string $title = null,
        public ?string $doi = null,
        public ?array $authors = [],
        public ?int $year = null,
        public ?int $page_count = null,
        public string $type = 'article',
        public ?array $citations = [],
    ) {}
}
