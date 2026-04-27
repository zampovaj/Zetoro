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
            'Updated at' => $this->item->updated_at,
        ],
        'folder' => [
            'Name' => $name,
            'Parent folder' => $this->parentName ?? '',
            'Created at' => $this->item->created_at->format('d.m.Y, H:i:s'),
            'Updated at' => $this->item->updated_at,
        ],
        'article' => [
            'Name' => $name,
            'DOI' => $this->item->metadata->doi ?? '',
            'Authors' => $this->item->metadata->authors ?? '',
            'Year' => $this->item->metadata->year ?? '',
            'Page count' => $this->item->metadata->page_count ?? '',
            'Type' => $this->item->metadata->type ?? '',
            'Citations' => $this->item->metadata->citations ?? '',
            'Created at' => $this->item->created_at,
            'Updated at' => $this->item->updated_at,
        ],
        default => [],
    };
@endphp

<div
    class=" hidden xl:flex absolute top-0 right-0 h-full w-80 flex-col
            bg-zinc-50 dark:bg-zinc-900
            border-l border-zinc-200 dark:border-zinc-700 z-20">

    <div class="p-6 w-ful dark:border-zinc-700 flex justify-between items-start ">
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

        @if ($this->type != null)

            @if($this->item != null)
                <x-inspector.section-list title="Properties" icon="document-text" :items="$properties">
                </x-inspector.section-list>
            @endif

            <x-inspector.inspector-section title="Annotations" icon="pencil">

                @forelse ($this->files->filter(fn($f) => $f->annotations->isNotEmpty()) as $file)
                    @if (!$loop->first)
                        <div class="h-2"></div>
                    @endif

                    <div class="text-zinc-300 text-mb text-center bg-zinc-700 rounded-lg py-3">
                        {{ $file->name }}
                    </div>

                    <livewire:livewire.inspector-annotations-section :file="$file">
                    </livewire:livewire.inspector-annotations-section>
                @empty
                    <span class="font-normal">
                        {{ 'No annotations available.' }}
                    </span>
                @endforelse

            </x-inspector.inspector-section>
        @else
            <div class="flex text-sm flex-col min-h-[80vh] text-zinc-400 items-center justify-center">
                <div>
                    <flux:icon.exclamation-triangle />
                </div>
                <div>
                    No item selected.
                </div>
            </div>

        @endif

    </div>

</div>
