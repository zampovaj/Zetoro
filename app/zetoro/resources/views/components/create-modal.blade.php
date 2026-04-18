@props(['action' => 'create', 'name', 'title', 'subtitle' => ''])

<flux:modal :name="$name" class="min-w-[400px]">
    <form wire:submit="save" class="space-y-6">

        <div>
            <flux:heading size="lg">{{ $title }}</flux:heading>
            @if ($subtitle)
                <flux:subheading>{{ $subtitle }}</flux:subheading>
            @endif
        </div>

        <div class="space-y-4">
            {{ $slot }}
        </div>

        <div class="flex justify-end gap-2">
            <flux:button type="button" variant="ghost"
                x-on:click="$dispatch('modal-close', { name: '{{ $name }}' })">
                Cancel
            </flux:button>

            <flux:button type="submit" variant="primary">
                {{ $action === 'create' ? 'Create' : 'Save changes' }}
            </flux:button>
        </div>

    </form>
</flux:modal>
