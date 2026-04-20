<?php

namespace App\Observers;

use App\Models\Article;

class ArticleObserver
{
    public function deleting(Article $article)
    {
        foreach($article->files as $file) {
            $file->delete();
        }
    }
}
