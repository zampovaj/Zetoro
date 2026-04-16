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
    <div class="group flex items-center justify-between py-1 px-2 hover:bg-zinc-200 dark:hover:bg-zinc-800 rounded-md cursor-pointer transition-colors"
        @if ($isExpandable && $hasChildren) @click="expanded = !expanded"
        @elseif ($type === 'file')
            wire:dblclick='openFile("{{ $item->id }}")' @endif>

        <div class="flex items-center gap-2 overflow-hidden">
            <div class="w-4 h-4 flex items-center justify-center">
                @if ($isExpandable && $hasChildren)
                    <flux:icon.chevron-right class="size-3 text-zinc-400 transition-transform duration-200"
                        x-bind:class="expanded ? 'rotate-90' : ''" />
                @endif
            </div>

            <flux:icon dynamic :name="$icon" class="size-4 text-zinc-400" />

            <span class="truncate text-zinc-700 dark:text-zinc-300">
                {{ $type === 'article' ? $item->metadata->title ?? 'Untitled' : $item->name }}
            </span>
        </div>

        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">

            <button wire:click.stop='triggerEdit("{{ $type }}", "{{ $item->id }}")'
                class="p-1 text-zinc-400 hover:text-blue-500 rounded">
                <flux:icon.pencil class="size-3" />
            </button>

            @if ($isExpandable)
                <button wire:click.stop='triggerCreate("{{ $type }}", "{{ $item->id }}")'
                    class="p-1 text-zinc-400 hover:text-green-500 rounded">
                    <flux:icon.plus class="size-3" />
                </button>
            @endif

            <button wire:click.stop='triggerDelete("{{ $type }}", "{{ $item->id }}")'
                class="p-1 text-zinc-400 hover:text-red-500 rounded">
                <flux:icon.trash class="size-3" />
            </button>
        </div>
    </div>

    @if ($isExpandable && $hasChildren)

        @if ($type === 'folder')
            @foreach ($allFolders->where('parent_id', $item->id) as $folder)
                @include('components.explorer-node', [
                    'type' => 'folder',
                    'item' => $folder,
                    'allFolders' => $allFolders,
                    'level' => $level + 1
                ])
            @endforeach

            @foreach ($item->articles as $article)
                @include('components.explorer-node', [
                    'type' => 'article',
                    'item' => $article,
                    'allFolders' => $allFolders,
                    'level' => $level + 1
                ])
            @endforeach

        @elseif ($type === 'article')
            @foreach ($item->files as $file)
                @include('components.explorer-node', [
                    'type' => 'file',
                    'item' => $file,
                    'allFolders' => $allFolders,
                    'level' => $level + 1
                ])
            @endforeach
        @endif

    @endif
</div>
