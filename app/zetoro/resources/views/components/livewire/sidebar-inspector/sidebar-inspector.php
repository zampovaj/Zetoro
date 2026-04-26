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
    public ?string $type = null;
    public ?string $itemId = null;

    public Collection $annotations;
    public Collection $children;
    public Collection $parents;

    public ?string $parentName = null;

    public function mount() {
        $this->annotations = collect();
        $this->children = collect();
        $this->parents = collect();
    }

    #[On('load-inspector')]
    public function load(string $type, string $itemId)
    {
        $this->type = $type;
        $this->itemId = $itemId;

        if ($this->type === 'file') {
            $this->item = File::with('annotations')->find($itemId);
            $this->annotations = $this->item?->annotations;
            $this->parentName = Article::findOrFail($this->item?->article_id)->metadata->title;
        } elseif ($this->type === 'article') {
            $this->item = Article::with('files')->find($itemId);
            $this->parents = $this->item?->folders;
            $this->children = $this->item?->files;
        } else if ($this->type === 'folder') {
            $this->item = Folder::with('articles.files')->find($itemId);
            $this->children = $this->item?->articles;
            $this->parentName = Folder::find($this->item?->parent_id)?->name ?? 'None';
        }
    }
};
