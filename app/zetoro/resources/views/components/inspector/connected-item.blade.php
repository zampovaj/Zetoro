@props(['type', 'name', 'id'])

@php
    $icon = match ($type) {
        'folder' => 'folder',
        'article' => 'book-open',
        'file' => 'document-text',
    };
@endphp

<div class="group relative flex items-center flex-row gap-2 font-semibold cursor-pointer transition-all ease-in-out duration-200
        hover:text-zinc-200 hover:pl-2
        before:content-[''] before:absolute before:left-0 before:top-0 before:h-full before:w-0 
        before:bg-zinc-400 before:transition-all before:duration-100 before:delay-0
        hover:before:w-0.5 hover:before:delay-[150ms]"
    wire:click="load('{{ $type }}', '{{ $id }}')">

    <flux:icon dynamic :name="$icon" class="size-4 text-zinc-400 transition-colors duration-200 group-hover:text-zinc-200" />

    <span class="truncate"> {{ $name }} </span>

</div>
