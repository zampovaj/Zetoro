<?php

namespace App\Livewire\Forms;

use App\Models\Article;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ArticleForm extends Form
{
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
}
