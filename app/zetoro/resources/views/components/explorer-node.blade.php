@props(['type', 'item', 'allFolders', 'level' => 0])

@use('App\Models\Folder')
@use('App\Models\Article')
@use('App\Models\File')


@php

    /** @var \App\Models\Folder|\App\Models\Article|\App\Models\File $item */
    /** @var \Illuminate\Support\Collection $allFolders */
    /** @var string $type */
    /** @var int $level */

    $isExpandable = in_array($type, ['folder', 'article']);
    $hasChildren = false;

    if ($type === 'folder') {
        $hasChildren = $item->articles->isNotEmpty() || $allFolders->where('parent_id', $item->id)->isNotEmpty();
    } elseif ($type === 'article') {
        $hasChildren = $item->files->isNotEmpty();
    }

    $icon = match ($type) {
        'folder' => 'folder',
        'article' => 'book-open',
        'file' => 'document-text',
    };

@endphp

<div x-data="{ expanded: false }" class="w-full text-sm select-none">
    <div class=" group flex items-center justify-between py-1 px-2 hover:bg-zinc-200 dark:hover:bg-zinc-800 rounded-md cursor-pointer transition-colors"
        :style="{ paddingLeft: {{ $level * 8 }} + 'px' }"
        @if ($isExpandable && $hasChildren) @click="expanded = !expanded"
        @elseif ($type === 'file')
            wire:click='openFile("{{ $item->id }}", "{{ $item->name }}")' @endif>

        <div class="flex items-center gap-2 min-w-0 overflow-hidden">
            <div class="w-4 h-4 flex-none flex items-center justify-center">
                @if ($isExpandable && $hasChildren)
                    <flux:icon.chevron-right class="size-3 text-zinc-400 transition-transform duration-200"
                        x-bind:class="expanded ? 'rotate-90' : ''" />
                @endif
            </div>

            <flux:icon dynamic :name="$icon" class="size-4 text-zinc-400" />

            <span class="truncate text-zinc-400 min-w-0">
                {{ $type === 'article' ? $item->metadata->title ?? 'Untitled' : $item->name }}
            </span>
        </div>

        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">

            <x-action-button
                action="triggerEdit"
                :type="$type"
                :itemId="$item->id"
                colorClass="text-zinc-400 hover:text-blue-500"
                icon="pencil" >
            </x-action-button>

            @if ($type === 'article')
                <x-action-button
                    action="triggerCreate"
                    type="file"
                    :itemId="$item->id"
                    colorClass="text-zinc-400 hover:text-green-500"
                    icon="plus" >
                </x-action-button>

            @elseif ($type === 'folder')
                <x-create-dropdown :itemId="$item->id" />
            @endif

            <x-action-button
                action="triggerDelete"
                :type="$type"
                :itemId="$item->id"
                colorClass="text-zinc-400 hover:text-red-500"
                icon="trash" >
            </x-action-button>

            <x-action-button
                action="triggerInspector"
                :type="$type"
                :itemId="$item->id"
                colorClass="text-zinc-400 hover:text-yellow-500"
                icon="bars-3-bottom-right" >
            </x-action-button>
        </div>
    </div>

    @if ($isExpandable && $hasChildren)

        <div x-show="expanded" x-collapse>

            @if ($type === 'folder')
                @foreach ($allFolders->where('parent_id', $item->id) as $folder)
                    <x-explorer-node
                        type="folder"
                        :item="$folder"
                        :allFolders="$allFolders"
                        :level="$level + 1" >
                    </x-explorer-node>
                @endforeach

                @foreach ($item->articles as $article)
                    <x-explorer-node
                        type="article"
                        :item="$article"
                        :allFolders="$allFolders"
                        :level="$level + 1" >
                    </x-explorer-node>
                @endforeach

            @elseif ($type === 'article')
                @foreach ($item->files as $file)
                    <x-explorer-node
                        type="file"
                        :item="$file"
                        :allFolders="$allFolders"
                        :level="$level + 1" >
                    </x-explorer-node>
                @endforeach
            @endif

        </div>
    @endif
</div>
