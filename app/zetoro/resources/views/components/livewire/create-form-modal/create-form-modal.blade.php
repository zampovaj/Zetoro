<div>
    @php
        $actionName = $mode === 'create' ? 'Create New ' : 'Edit ';
        $modalTitle = $actionName . ($type ? ucfirst($type) : 'Item');
        $modalSubtitle = $parentId ? 'Adding to the selected folder.' : 'Adding to the root workspace.';
    @endphp

    <x-create-modal :action="$mode" name="create-modal" :title="$modalTitle" :subtitle="$modalSubtitle">

        @if ($type === 'annotation')
            <flux:textarea wire:model="annotationForm.note" label="Note" />
            
        @elseif ($type === 'article')
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

            <div x-data="{ isDropping: false }" x-on:dragover.prevent="isDropping = true"
                x-on:dragleave.prevent="isDropping = false"
                x-on:drop.prevent=
                    "isDropping = false; $wire.upload('fileForm.uploadFile', $event.dataTransfer.files[0])"
                class="relative flex flex-col items-center justify-center p-8 border-2 border-dashed rounded-lg transition-colors"
                x-bind:class="{
                    'border-blue-500 bg-blue-50 dark:bg-blue-900/20': isDropping,
                    'border-zinc-300 dark:border-zinc-700': !isDropping
                }">

                <input type="file" id="pdf-upload" wire:model="fileForm.uploadFile" class="hidden" accept=".pdf" />

                <div class="text-center">
                    <flux:icon.document-arrow-up class="size-8 mx-auto text-zinc-400 mb-3" />

                    <p class="text-sm text-zinc-600 dark:text-zinc-400">
                        <label for="pdf-upload" class="font-medium text-blue-500 hover:underline cursor-pointer">
                            Click to browse
                        </label>
                        or drag and drop
                    </p>
                    <p class="text-xs text-zinc-500 mt-1">PDF up to 50MB</p>
                </div>

                @if ($fileForm->uploadFile)
                    <div
                        class="absolute inset-0 bg-white/90 dark:bg-zinc-900/90 flex items-center justify-center rounded-lg border border-green-500">
                        <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                            <flux:icon.check-circle class="size-5" />
                            <span class="text-sm font-medium truncate max-w-[200px]">
                                {{ $fileForm->uploadFile->getClientOriginalName() }}
                            </span>
                        </div>
                    </div>
                @endif

                <div wire:loading wire:target="fileForm.uploadFile"
                    class="absolute inset-0 bg-white/90 dark:bg-zinc-900/90 flex items-center justify-center rounded-lg">
                    <div class="text-sm font-medium text-blue-500 animate-pulse">
                        Uploading to server...
                    </div>
                </div>
            </div>

            @error('fileForm.uploadFile')
                <div class="text-sm text-red-500">{{ $message }}</div>
            @enderror

        @endif

    </x-create-modal>
</div>
