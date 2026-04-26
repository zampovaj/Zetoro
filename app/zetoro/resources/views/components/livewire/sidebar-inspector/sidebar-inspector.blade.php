@use('Illuminate\Support\Str')

@php
    $name = match ($this->type) {
        'file', 'folder' => $item->name,
        'article' => $item->metadata->title,
        default => '',
    };

    $properties = match ($this->type) {
        'file' => [
            'Name' => $name,
            'Path' => $this->item->path,
            'Parent article' => $this->parentName,
            'Created at' => $this->item->created_at,
        ],
        'folder' => [
            'Name' => $name,
            'Parent folder' => $this->parentName ?? '',
            'Created at' => $this->item->created_at->format('d.m.Y, H:i:s'),
        ],
        'article' => [
            'Name' => $name,
            'Created at' => $this->item->created_at,
            'DOI' => $this->metadata->doi ?? '',
            'Authors' => $this->metadata->authors ?? '',
            'Year' => $this->metadata->year ?? '',
            'Page count' => $this->metadata->page_count ?? '',
            'Type' => $this->metadata->type ?? '',
            'Citations' => $this->metadata->citations ?? '',
        ],
        default => [],
    };
@endphp

<div
    class=" hidden xl:flex absolute top-0 right-0 h-full w-80 flex-col
            bg-zinc-50 dark:bg-zinc-900
            border-l border-zinc-200 dark:border-zinc-700 z-20">

    <div class="p-6 w-ful dark:border-zinc-700 flex justify-between items-start">
        <div>
            <flux:heading size="lg" class="truncate max-w-50">
                {{ $name }}
            </flux:heading>
            <flux:subheading class="uppercase text-xs tracking-wider mt-1">
                {{ $this->type }}
            </flux:subheading>
        </div>
    </div>

    <div class="overflow-y-auto">

        @if ($this->item != null)
            <x-inspector.section-list title="Properties" icon="document-text" :items="$properties" :defaultExpanded="true">

            </x-inspector.section-list>

            <x-inspector.inspector-section title="Properties" icon="pencil">
                <div class="text-xs text-zinc-500 space-y-2">
                    <p>Created: 1.1.2000</p>
                </div>
                <div class="text-xs text-zinc-500 space-y-2">
                    <p>Created: 1.1.2000</p>
                </div>
                <div class="text-xs text-zinc-500 space-y-2">
                    <p>Created: 1.1.2000</p>
                </div>
                <div class="text-xs text-zinc-500 space-y-2">
                    <p>Created: 1.1.2000</p>
                </div>
            </x-inspector.inspector-section>

            <x-inspector.inspector-section title="Properties" icon="pencil">
                <div class="text-xs text-zinc-500 space-y-2">
                    <p>Created: 1.1.2000</p>
                </div>
                <div class="text-xs text-zinc-500 space-y-2">
                    <p>Created: 1.1.2000</p>
                </div>
                <div class="text-xs text-zinc-500 space-y-2">
                    <p>Created: 1.1.2000</p>
                </div>
                <div class="text-xs text-zinc-500 space-y-2">
                    <p>Created: 1.1.2000</p>
                </div>
            </x-inspector.inspector-section>
        
        @else

        <div class="flex text-mb flex-col min-h-[80vh] text-zinc-400 items-center justify-center">
            <div>
                <flux:icon.exclamation-triangle />
            </div>
            <div >
                No item selected.
            </div>
        </div>

        @endif

    </div>

</div>
