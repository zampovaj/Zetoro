<?php

use App\Livewire\Forms\ArticleForm;
use App\Livewire\Forms\FileForm;
use App\Livewire\Forms\FolderForm;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?string $type = null;

    public ?string $parentId = null;

    public ArticleForm $articleForm;

    public FolderForm $folderForm;

    public FileForm $fileForm;

    #[On('open-create-modal')]
    public function loadModal(string $type, ?string $parentId = null)
    {
        $this->type = $type;
        $this->parentId = $parentId;

        Flux::modal('create-modal')->show();
    }

    public function save()
    {
        $newItem = null;

        if ($this->type === 'article') {
            $newItem = $this->articleForm->store($this->parentId);
        } elseif ($this->type === 'folder') {
            $newItem = $this->folderForm->store($this->parentId);
        } elseif ($this->type === 'file') {
            $newItem = $this->fileForm->store($this->parentId);
        }

        $this->dispatch('modal-close', name: 'create-modal');
        // passing just id, causa ei need to refetch the item WITH relations so it makes no sense ot pass around the whole thing
        $this->dispatch('item-created', type: $this->type, itemId: $newItem->id);
    }
};
