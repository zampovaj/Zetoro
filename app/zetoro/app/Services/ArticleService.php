<?php

namespace App\Services;

use App\Contracts\IMetadataExtractor;
use App\DTOs\CreateArticleRequest;
use App\Models\Article;
use App\Models\File;
use App\Models\Settings;
use Illuminate\Support\Facades\DB;

class ArticleService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private IMetadataExtractor $extractor
    ) {}

    public function handleNewArticle(CreateArticleRequest $requestData): void
    {
        $metadata = $this->extractor->extractArticleMetadataDto($requestData->url);

        // laravel handles try/catch and save/commit/rollback stuff on its own
        // black magic but less boilerplate
        // if i really need to i can do it manually, but here i really dont need to

        // scope is different than dotnet
        // local functions are oblivious to any varibales
        // i need to pass them manually
        DB::transaction(function () use ($metadata, $requestData): void {
            $article = Article::create([
                'metadata' => $metadata,
                'url' => $requestData->url,
            ]);

            File::create([
                'article_id' => $article->id,
                'name' => $requestData->fileName,
                'path' => $requestData->pdfPath,
            ]);

            $targetFolder = Settings::find('last_active_folder');

            if ($targetFolder && ! empty($targetFolder)) {
                $article->attach($targetFolder->value);
            }
        });
    }
}
