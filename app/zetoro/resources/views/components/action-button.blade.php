@props(['action', 'type', 'itemId', 'icon', 'colorClass' => 'text-zinc-400 hover:text-zinc-500'])

<button wire:click.stop="{{ $action }}('{{ $type }}', '{{ $itemId }}')"
    class="p-1 {{ $colorClass }} rounded cursor-pointer">

    <flux:icon dynamic :name="$icon" class="size-3" />

</button>
