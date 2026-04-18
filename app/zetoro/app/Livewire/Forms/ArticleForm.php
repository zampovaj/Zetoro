<?php

namespace App\Livewire\Forms;

use App\Models\Article;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ArticleForm extends Form
{
    public ?Article $article = null;

    #[Validate('required|string|min:1')]
    public string $title = '';

    #[Validate('nullable|url')]
    public string $url = '';

    #[Validate('nullable|string')]
    public string $doi = '';

    #[Validate('nullable|array')]
    public array $authors = [];

    #[Validate('nullable|integer|digits:4')]
    public ?int $year = null;

    #[Validate('nullable|integer|min:1')]
    public ?int $pageCount = null;

    #[Validate('nullable|string')]
    public string $type = '';

    #[Validate('nullable|array')]
    public array $citations = [];

    public function setArticle(Article $article)
    {
        $this->article = $article;
        $this->url = $article->url ?? '';
        $this->title = $article->metadata->title ?? '';
        $this->doi = $article->metadata->doi ?? '';
        $this->authors = $article->metadata->authors ?? [];
        $this->year = $article->metadata->year;
        $this->pageCount = $article->metadata->page_count;
        $this->type = $article->metadata->type ?? 'article';
        $this->citations = $article->metadata->citations ?? [];
    }

    public function store(?string $parentId = null): Article
    {
        $parentId = $parentId ?: null;

        $this->validate();

        $article = Article::create([
            'url' => $this->url,
            'metadata' => [
                'title' => $this->title,
                'doi' => $this->doi ?: null,
                'authors' => $this->authors,
                'year' => $this->year,
                'page_count' => $this->pageCount,
                'type' => $this->type ?: 'article',
                'citations' => $this->citations,
            ],
        ]);

        if ($parentId) {
            $article->folders()->attach($parentId);
        }

        $this->reset();

        return $article;
    }

    public function update(): Article
    {
        $this->validate();

        $this->article->update([
            'url' => $this->url,
            'metadata' => [
                'title' => $this->title,
                'doi' => $this->doi ?: null,
                'authors' => $this->authors,
                'year' => $this->year,
                'page_count' => $this->pageCount,
                'type' => $this->type ?: 'article',
                'citations' => $this->citations,
            ],
        ]);

        $article = $this->article;

        $this->reset();

        return $article;
    }
}
