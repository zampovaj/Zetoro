<?php

use App\Models\Article;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?string $type = 'root';

    public ?string $itemId = null;

    public array $filterChoices = ['highlights', 'notes'];

    #[Computed]
    public function item(): ?Model
    {
        return match ($this->type) {
            'root' => null,
            'file' => File::with('annotations')->find($this->itemId),
            'article' => Article::with('files.annotations')->find($this->itemId),
            'folder' => Folder::with(
                'allChildren.articles.files.annotations',
                'articles.files.annotations'
            )->find($this->itemId),
            default => null,
        };
    }

    #[Computed]
    public function files(): Collection
    {
        $item = $this->item();

        return match ($this->type) {
            'file' => $item ? collect([$item]) : collect(),
            'article' => $item?->files ?? collect(),
            'folder' => $item ? $this->getNestedFiles($item) : collect(),
            'root' => File::with('annotations')->get(),
            default => collect(),
        };
    }

    #[Computed]
    public function children(): Collection
    {
        $item = $this->item();

        return match ($this->type) {
            'article' => $item?->files ?? collect(),
            'folder' => ($item?->children ?? collect())->concat($item?->articles ?? collect()),
            default => collect(),
        };
    }

    #[Computed]
    public function parents(): Collection
    {
        $item = $this->item();

        return match ($this->type) {
            'file' => collect([Article::find($item?->article_id)])->filter(),
            'article' => $item?->folders ?? collect(),
            'folder' => collect([Folder::find($item?->parent_id)])->filter(),
            default => collect(),
        };
    }

    #[Computed]
    public function parentName(): string
    {
        return match ($this->type) {
            'file' => $this->parents()->first()?->metadata->title ?? 'None',
            'folder' => $this->parents()->first()?->name ?? 'None',
            default => 'None',
        };
    }

    public function mount()
    {
        $this->load('root', null);
    }

    #[On('item-deleted')]
    public function remove(array $fileIds, ?string $itemId)
    {
        if ($this->itemId === $itemId) {
            $this->load('root', null);
        } else {
            $this->refresh();
        }
    }

    #[On('item-updated')]
    #[On('item-created')]
    #[On('annotation-item-created')]
    #[On('annotation-item-updated')]
    #[On('annotation-item-deleted')]
    public function refresh()
    {
        unset($this->item);
        unset($this->files);
        unset($this->children);
        unset($this->parents);
        unset($this->parentName);
    }

    #[On('load-inspector')]
    public function load(string $type, ?string $itemId)
    {
        $this->type = $type;
        $this->itemId = $itemId;

        $this->refresh();
    }

    private function getNestedFiles(Folder $folder): Collection
    {
        $files = $folder->articles->flatMap->files;

        foreach ($folder->allChildren as $subFolder) {
            $files = $files->concat($this->getNestedFiles($subFolder));
        }

        return $files;
    }

    public function filterFiles(): Collection
    {
        $files = $this->files()->filter(fn ($f) => $f->annotations->isNotEmpty());

        $showNotes = in_array('notes', $this->filterChoices);
        $showHighlights = in_array('highlights', $this->filterChoices);

        if ($showNotes && $showHighlights) {
            return $files;
        }

        if (! $showNotes && ! $showHighlights) {
            return collect();
        }

        return $files->filter(function ($f) use ($showNotes) {
            return $f->annotations->contains(function ($a) use ($showNotes) {
                return $showNotes ? filled($a->note) : blank($a->note);
            });
        });
    }
};
