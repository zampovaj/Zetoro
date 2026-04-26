@props(['id', 'fileName', 'pageNumber', 'text', 'note' => null])

<div class="grid grid-cols-[1fr_1.25rem] gap-2 py-2 w-full hover:scale-101 cursor-pointer transition-transform duration-300 ease-in-out"
    wire:click="openAnnotation('{{ $id }}', '{{ $pageNumber }}')">
    <div class="flex flex-col min-w-0">
        <div class="pt-1 text-zinc-600 text-xs uppercase">Text</div>
        <div class=" border-zinc-400 w-full overflow-hidden break-words">
            {{ $text }}
        </div>

        
        @if ($note)
            <div class="pt-2 text-zinc-600 text-xs uppercase">Note</div>
            <div class="border-zinc-400 w-full mt-1 break-words">
                {{ $note }}
            </div>
        @endif
    </div>
    <div class="flex whitespace-nowrap pt-2 pr-2 text-zinc-400 font-semibold justify-center">
        <span>
            {{ $pageNumber }}
        </span>
    </div>
</div>
