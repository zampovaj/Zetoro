<div>
    @php
        $modalTitle = $type ? 'Create New ' . ucfirst($type) : 'Create New Item';
        $modalSubtitle = $parentId ? 'Adding to the selected folder.' : 'Adding to the root workspace.';
    @endphp

    <x-create-modal
        name="create-modal"
        :title="$modalTitle"
        :subtitle="$modalSubtitle">

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
        
    </x-create-modal>
</div>