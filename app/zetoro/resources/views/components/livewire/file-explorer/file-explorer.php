<?php

use App\Models\Article;
use App\Models\Folder;
use App\Services\DeleteService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public Collection $allFolders;

    public Collection $rootFolders;

    public Collection $orphanArticles;

    public function mount(): void
    {
        $this->loadData();
    }

    #[On('item-deleted')]
    public function reload(): void
    {
        $this->loadData();
    }

    private function loadData(): void
    {
        $this->allFolders = Folder::with('articles.files')->get();
        $this->rootFolders = $this->allFolders->whereNull('parent_id');
        $this->orphanArticles = Article::doesntHave('folders')->with('files')->get();
    }

    public function triggerEdit(string $type, string $itemId): void
    {
        $this->dispatch('open-edit-modal', type: $type, itemId: $itemId);
    }

    public function triggerCreate(string $type, string $itemId): void
    {
        $this->dispatch('open-create-modal', type: $type, parentId: $itemId);
    }

    public function triggerDelete(DeleteService $service, string $type, $itemId): void
    {
        $idsToRemove = $service->delete($type, $itemId);

        $this->dispatch('item-deleted', fileIds: $idsToRemove);
    }

    public function triggerInspector(string $type, string $itemId)
    {
        $this->dispatch('load-inspector', type: $type, itemId: $itemId);
    }

    public function openFile(string $fileId, string $title): void
    {
        $this->dispatch('request-file-open', fileId: $fileId, title: $title);
    }

    #[On('item-created')]
    public function handleItemCreated(string $type, string $itemId)
    {
        if ($type === 'folder') {
            $newFolder = Folder::with('articles.files')->find($itemId);

            $this->allFolders->push($newFolder);

            if (is_null($newFolder->parent_id)) {
                $this->rootFolders->push($newFolder);
            }
        } elseif ($type === 'article') {
            $newArticle = Article::with('files')->find($itemId);

            if ($newArticle->folders()->count() == 0) {
                $this->orphanArticles->push($newArticle);
            } else {
                // its actually cheaper to reload then search inside the tree
                $this->loadData();
            }
        } else {
            $this->loadData();
        }
    }

    #[On('item-updated')]
    public function HandleItemUpdated(string $type, string $itemId)
    {
        $this->loadData();
    }
};
