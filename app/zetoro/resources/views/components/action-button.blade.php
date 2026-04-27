@props(['action', 'type', 'itemId', 'icon', 'colorClass' => 'text-zinc-400 hover:text-zinc-500', 'size' => 3])

<button wire:click.stop="{{ $action }}('{{ $type }}', '{{ $itemId }}')"
    class="p-1 {{ $colorClass }} rounded cursor-pointer">

    <flux:icon dynamic :name="$icon" class="size-{{ $size }}" />

</button>
