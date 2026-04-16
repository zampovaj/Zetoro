<div>
    <flux:modal name="create-modal" class="min-w-[400px]">

        <form wire:submit="save" class="space-y-6">

            {{-- Dynamic Headers based on the $type --}}
            <div>
                <flux:heading size="lg">
                    Create New {{ ucfirst($type) }}
                </flux:heading>
                <flux:subheading>
                    @if ($parentId)
                        Adding to the selected folder.
                    @else
                        Adding to the root workspace.
                    @endif
                </flux:subheading>
            </div>

            {{-- The Input Field --}}
            <flux:input wire:model="name" label="{{ $type === 'article' ? 'Article Title' : 'Folder Name' }}"
                placeholder="Enter name..." required />

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-2">
                <flux:button type="button" variant="ghost"
                    x-on:click="$dispatch('modal-close', { name: 'create-modal' })">
                    Cancel
                </flux:button>

                <flux:button type="submit" variant="primary">
                    Create
                </flux:button>
            </div>

        </form>

    </flux:modal>
</div>
