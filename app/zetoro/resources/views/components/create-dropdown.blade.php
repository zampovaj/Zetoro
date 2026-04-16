@props(['itemId'])

<flux:dropdown>
    <!-- Trigger button -->
    <button
        type="button"
        class="p-1 text-zinc-400 hover:text-green-500 rounded"
        @click.stop
    >
        <flux:icon.plus class="size-3" />
    </button>

     <flux:menu>
        <flux:menu.item wire:click.stop="triggerCreate('folder', '{{ $itemId }}')">
            Folder
        </flux:menu.item>
        <flux:menu.item wire:click.stop="triggerCreate('article', '{{ $itemId }}')">
            Article
        </flux:menu.item>
    </flux:menu>
</flux:dropdown>
