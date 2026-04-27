@props(['title', 'icon', 'items', 'emptyMessage' => 'No information available', 'defaultExpanded' => false])

<div>
    <x-inspector.inspector-section :title="$title" :icon="$icon" :defaultExpanded="$defaultExpanded">
        @forelse ($items as $name => $value)
            <p>
                <span class="font-semibold">{{ $name }}:</span>
                @if (is_array($value))
                    {{ implode(', ', $value) }}
                @else
                    {{ $value }}
                @endif
            </p>
        @empty
            <span class="font-normal">
                {{ $emptyMessage }}
            </span>
        @endforelse
    </x-inspector.inspector-section>
</div>
