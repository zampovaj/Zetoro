<?php

namespace App\Jobs;

use App\DTOs\CreateArticleRequest;
use App\Services\ArticleService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessExtensionPayload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public function __construct(
        public CreateArticleRequest $requestData
    ) {}

    public function handle(ArticleService $service): void
    {
        try {
            $service->handleNewArticle($this->requestData);
        } catch (Exception $ex) {
            \Log::error("Saving failed for url: {$this->requestData->url}. Error: {$ex->getMessage()}");
            throw $ex;
        }
    }
}
