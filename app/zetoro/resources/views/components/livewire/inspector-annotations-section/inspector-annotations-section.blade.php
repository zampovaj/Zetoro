
<div>
    @forelse ($this->file->annotations->sortBy('page') as $annotation)
        <x-inspector.highlight-card
            :id="$annotation->id"
            :fileName="$this->file->name"
            :pageNumber="$annotation->page"
            :text="$annotation->text"
            :note="$annotation->note" >

        </x-inspector.highlight-card>

        @if(!$loop->last)
            <flux:separator></flux:separator>
        @endif
    @empty
        
    @endforelse
</div>