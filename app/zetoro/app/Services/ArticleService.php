<?php

namespace App\Services;

use App\Contracts\IMetadataExtractor;
use App\DTOs\ArticleMetadataDto;
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
        $this->saveArticle(
            metadata: $metadata,
            url: $requestData->url,
            pdfPath: $requestData->pdfPath,
            fileName: $requestData->fileName,
        );
    }

    // later add method for saving manual article
    // public function addArticle(NewArticleDto $request):void {
    //     $this->saveArticle(
    //         metadata: $metadata,
    //         url: $requestData->url,
    //         pdfPath: $requestData->pdfPath,
    //         fileName: $requestData->fileName,
    //     );
    // }

    private function saveArticle(ArticleMetadataDto $metadata, string $url, string $pdfPath, string $fileName): void
    {
        // laravel handles try/catch and save/commit/rollback stuff on its own
        // after rollback rethrows the exception
        // black magic but less boilerplate
        // if i really need to i can do it manually, but here i really dont need to

        // scope is different than dotnet
        // local functions are oblivious to any varibales
        // i need to pass them manually
        DB::transaction(function () use ($metadata, $url, $pdfPath, $fileName): void {
            $article = Article::create([
                'metadata' => $metadata,
                'url' => $url,
            ]);

            File::create([
                'article_id' => $article->id,
                'name' => $fileName,
                'path' => $pdfPath,
            ]);

            $targetFolder = Settings::find('last_active_folder');

            if ($targetFolder && ! empty($targetFolder)) {
                $article->attach($targetFolder->value);
            }
        });
    }
}
