@props(['items', 'type', 'defaultExpanded' => false])

<div class="pl-1 flex-row space-y-2">
    @forelse ($items as $item)
        <x-inspector.connected-item
            :type="$type"
            :name="$type === 'article' ? $item->metadata->title : $item->name"
            :id="$item->id" >
        </x-inspector.connected-item>
    @empty
    @endforelse
</div>
