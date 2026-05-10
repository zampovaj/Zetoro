@props(['type', 'name', 'id'])

@php
    $icon = match ($type) {
        'folder' => 'folder',
        'article' => 'book-open',
        'file' => 'document-text',
    };
@endphp

<div class="flex flex-row gap-2 font-semibold cursor-pointer transition-all ease-in-out hover:text-zinc-200 hover:pl-0.5"
    wire:click="load('{{ $type }}', '{{ $id }}')">

    <flux:icon dynamic :name="$icon" class="size-4 text-zinc-400" />
    
    <span> {{ $name }} </span>

</div>
