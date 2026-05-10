@props(['title', 'icon', 'defaultExpanded' => false])

<div>
    <flux:separator></flux:separator>

    <div x-data="{ expanded: {{ $defaultExpanded ? 'true' : 'false' }} }">
        <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-2 group cursor-pointer px-4"
            x-bind:class="expanded ? 'text-zinc-200  hover:text-zinc-300' : 'text-zinc-400  hover:text-zinc-200'">
            <div class="p-1 flex items-center gap-2 text-sm font-medium">
                <flux:icon dynamic :name="$icon" class="size-4 transition-colors" />
                <span class="text-sm font-medium">{{ $title }}</span>
            </div>
            <flux:icon.chevron-down class="size-3 text-zinc-400 transition-transform duration-200"
                x-bind:class="expanded ? 'rotate-180' : ''" />
        </button>

        <div x-show="expanded" x-collapse>
            <div class="p-4 pt-2 text-sm text-zinc-400 space-y-2">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
