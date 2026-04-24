<?php

namespace App\Services;

use App\Models\File;
use App\Models\Folder;
use App\Models\Article;
use App\Models\Annotation;

class DeleteService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(string $type, $itemId)
    {
        $idsToRemove = match ($type) {
            'file'    => [$itemId],
            'article' => File::whereArticleId($itemId)->pluck('id')->toArray(),
            'folder'  => [],
            'annotation' => [],
            default   => [],
        };
        match ($type) {
            'folder' => Folder::destroy($itemId),
            'article' => Article::destroy($itemId),
            'file' => File::destroy($itemId),
            'annotation' => Annotation::destroy($itemId)
        };

        return $idsToRemove;
    }
}
