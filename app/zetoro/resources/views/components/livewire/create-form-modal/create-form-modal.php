<?php

use App\Livewire\Forms\ArticleForm;
use App\Livewire\Forms\FileForm;
use App\Livewire\Forms\FolderForm;
use App\Models\Article;
use App\Models\File;
use App\Models\Folder;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public ?string $type = null;
    public ?string $parentId = null;
    public ?string $editItemId = null; // for update tracking
    public string $mode = 'create'; // create / edit

    public ArticleForm $articleForm;
    public FolderForm $folderForm;
    public FileForm $fileForm;

    #[On('open-create-modal')]
    public function loadCreateModal(string $type, ?string $parentId = null)
    {
        $this->mode = 'create';
        $this->type = $type;
        $this->editItemId = null;
        $this->parentId = $parentId;

        Flux::modal('create-modal')->show();
    }

    #[On('open-edit-modal')]
    public function loadEditModal(string $type, string $itemId)
    {
        $this->mode = 'edit';
        $this->type = $type;
        $this->editItemId = $itemId;
        $this->parentId = null;

        if ($type === 'folder') {
            $this->folderForm->setFolder(Folder::findOrFail($itemId));
        } elseif ($type === 'article') {
            $this->articleForm->setArticle(Article::findOrFail($itemId));
        } elseif ($type === 'file') {
            $this->fileForm->setFile(File::findOrFail($itemId));
        }

        Flux::modal('create-modal')->show();
    }

    public function save()
    {
        $item = null;

        if ($this->mode === 'create') {
            $item = match ($this->type) {
                'article' => $this->articleForm->store($this->parentId),
                'folder' => $this->folderForm->store($this->parentId),
                'file' => $this->fileForm->store($this->parentId),
            };
        } elseif ($this->mode === 'edit') {
            $item = match ($this->type) {
                'article' => $this->articleForm->update(),
                'folder' => $this->folderForm->update(),
                // 'file' => $this->fileForm->update(),
            };
        }

        Flux::modal('create-modal')->close();

        $eventName = $this->mode === 'create' ? 'item-created' : 'item-updated';
        // passing just id, causa ei need to refetch the item WITH relations so it makes no sense ot pass around the whole thing
        $this->dispatch($eventName, type: $this->type, itemId: $item->id);
    }
};
