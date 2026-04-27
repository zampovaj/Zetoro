<?php

use App\Models\Article;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?Model $item = null;

    public ?string $type = 'root';

    public ?string $itemId = null;

    public Collection $files;

    public Collection $children;

    public Collection $parents;

    public ?string $parentName = null;

    public function mount()
    {
        $this->files = collect();
        $this->children = collect();
        $this->parents = collect();
        $this->load('root', null);
    }

    // #[On('item-deleted')]
    public function remove()
    {
        $this->reset();
    }

    #[On('item-updated')]
    #[On('item-created')]
    public function refresh()
    {
        if ($this->item === null) {
            return;
        }
        $this->load($this->type, $this->itemId);
    }

    #[On('load-inspector')]
    public function load(string $type, ?string $itemId)
    {
        $this->type = $type;
        $this->itemId = $itemId;

        if ($this->type === 'file') {
            $this->item = File::with('annotations')->find($itemId);
            $this->parentName = Article::findOrFail($this->item?->article_id)->metadata->title;
            $this->files = $this->item ? collect([$this->item]) : collect();
        } elseif ($this->type === 'article') {
            $this->item = Article::with('files.annotations')->find($itemId);
            $this->parents = $this->item?->folders;
            $this->children = $this->item?->files;
            $this->files = $this->item?->files ?? collect();
        } elseif ($this->type === 'folder') {
            $this->item = Folder::with('articles.files.annotations')->find($itemId);
            $this->item?->load('allChildren.articles.files.annotations', 'articles.files.annotations');
            $this->children = $this->item?->articles;
            $this->parentName = Folder::find($this->item?->parent_id)?->name ?? 'None';
            $this->files = $this->item ? $this->getNestedFiles($this->item) : collect();
        } elseif ($this->type === 'root') {
            $this->item = null;
            $this->files = File::with('annotations')->get();
        }
    }

    public function getNestedFiles(Folder $folder)
    {
        $files = $folder->articles->flatMap->files;

        foreach ($folder->allChildren as $subFolder) {
            $files = $files->concat($this->getNestedFiles($subFolder));
        }

        return $files;
    }
};
