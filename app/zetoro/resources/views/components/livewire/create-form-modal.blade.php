<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\ArticleForm;
use App\Livewire\Forms\FolderForm;
use App\Livewire\Forms\FileForm;
use Flux\Flux;

new class extends Component {
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
        if ($this->type === 'article') {
            $this->articleForm->store($this->parentId);
        } elseif ($this->type === 'folder') {
            $this->folderForm->store($this->parentId);
        } elseif ($this->type === 'file') {
            $this->fileForm->store($this->parentId);
        }

        $this->dispatch('modal-close', name: 'create-modal');
        $this->dispatch('item-updated');
    }
};
?>

<div>
    @php
        $modalTitle = $type ? 'Create New ' . ucfirst($type) : 'Create New Item';
        $modalSubtitle = $parentId ? 'Adding to the selected folder.' : 'Adding to the root workspace.';
    @endphp

    @component('components.create-modal', [
        'name' => 'create-modal',
        'title' => $modalTitle,
        'subtitle' => $modalSubtitle,
    ])

        @if ($type === 'article')
            <flux:input wire:model="articleForm.title" label="Title" required />
            <flux:input wire:model="articleForm.url" label="Source URL" />

            <div class="flex gap-4">
                <flux:input wire:model="articleForm.doi" label="DOI" class="flex-1" />
                <flux:input wire:model="articleForm.year" label="Year" type="number" class="w-32" />
            </div>
        @elseif($type === 'folder')
            <flux:input wire:model="folderForm.name" label="Folder Name" required />
        @elseif($type === 'file')
            <flux:input wire:model="fileForm.name" label="File Name" required />
            <div class="border-2 border-dashed border-zinc-300 p-8 text-center rounded-md text-zinc-500">
                Drag and drop PDF here
            </div>
        @endif

    @endcomponent
</div>
