@props(['type', 'name', 'id'])


<p class="font-semibold cursor-pointer transition-all ease-in-out hover:text-zinc-200 hover:pl-0.5"
    wire:click="load('{{ $type }}', '{{ $id }}')">
    {{ $name }}
</p>
