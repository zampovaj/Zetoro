@use('Illuminate\Support\Str')

@php
    $name = match ($this->type) {
        'file', 'folder' => $this->item->name,
        'article' => $this->item->metadata->title,
        default => '',
    };

    $properties = match ($this->type) {
        'file' => [
            'Name' => $name,
            'Path' => $this->item->path,
            'Parent article' => $this->parentName,
            'Created at' => $this->item->created_at->format('d.m.Y, H:i:s'),
            'Updated at' => $this->item->updated_at->format('d.m.Y, H:i:s'),
        ],
        'folder' => [
            'Name' => $name,
            'Parent folder' => $this->parentName ?? '',
            'Created at' => $this->item->created_at->format('d.m.Y, H:i:s'),
            'Updated at' => $this->item->updated_at->format('d.m.Y, H:i:s'),
        ],
        'article' => [
            'Name' => $name,
            'DOI' => $this->item->metadata->doi ?? '',
            'Authors' => $this->item->metadata->authors ?? '',
            'Year' => $this->item->metadata->year ?? '',
            'Page count' => $this->item->metadata->page_count ?? '',
            'Type' => $this->item->metadata->type ?? '',
            'Citations' => $this->item->metadata->citations ?? '',
            'Created at' => $this->item->created_at->format('d.m.Y, H:i:s'),
            'Updated at' => $this->item->updated_at->format('d.m.Y, H:i:s'),
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
            <flux:heading size="lg" class="max-w-100">
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

            @if($this->children->count() > 0)
                <x-inspector.inspector-section title="Children" icon="list-bullet">

                    @if ($this->type === 'article')
                    
                        <x-inspector.section-items-list
                            :items="$this->item->files"
                            type="file" >
                        </x-inspector.section-items-list>
                    
                    @elseif ($this->type === 'folder')

                        <x-inspector.section-items-list
                            :items="$this->item->children"
                            type="folder" >
                        </x-inspector.section-items-list>

                        <x-inspector.section-items-list
                            :items="$this->item->articles"
                            type="article" >
                        </x-inspector.section-items-list>

                    @endif

                </x-inspector.inspector-section>
            @endif

            @if ($this->parents->count() > 0)
                <x-inspector.inspector-section title="Parents" icon="list-bullet">

                    <x-inspector.section-items-list
                        :items="$this->parents"
                        :type="$this->type === 'file' ? 'article' : 'folder'" >
                    </x-inspector.section-items-list>

                </x-inspector.inspector-section>
            @endif

            <x-inspector.inspector-section title="Annotations" icon="pencil">
                
                <div class="flex pb-2 flex-col [&_*]:!text-zinc-400 ">

                    <flux:checkbox.group class="pb-4 text-zinc-400" wire:model.live="filterChoices" label="Filter">
                        <flux:checkbox label="Highlights" value="highlights" />
                        <flux:checkbox label="Notes" value="notes" />
                    </flux:checkbox.group>
                
                    <flux:separator></flux:separator>

                </div>
        
                @forelse ($this->filterFiles() as $file)
                    @if (!$loop->first)
                        <div class="h-2"></div>
                    @endif

                    <livewire:livewire.inspector-annotations-section
                        :file="$file"
                        :filterChoices="$this->filterChoices"
                        :showName="($this->type != 'file')"
                        :wire:key="'file-'.$file->id.'-type-'.$this->type.'-filters-'.implode('-', $filterChoices)" >
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
