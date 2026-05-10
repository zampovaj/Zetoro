
<div>
    @if ($this->showName)
        <div class="flex justify-center w-full">
            <div class="w-full text-zinc-300 text-mb text-center bg-zinc-700 rounded-lg py-3 cursor-pointer transition-all duration-300 ease-in-out hover:bg-zinc-400 hover:text-black hover:scale-97 hover:mx-1"      
                wire:click="triggerOpenFile()" >
                {{ $file->name }}
            </div>
        </div>
    @endif

    @forelse ($this->file->annotations->sortBy('page') as $annotation)

        @if((in_array('highlights', $this->filterChoices) && blank($annotation->note)) ||
            (in_array('notes', $this->filterChoices) && filled($annotation->note)))

            <x-inspector.highlight-card
                :id="$annotation->id"
                :pageNumber="$annotation->page"
                :text="$annotation->text"
                :note="$annotation->note" >

            </x-inspector.highlight-card>

            @if(!$loop->last)
                <flux:separator></flux:separator>
            @endif

        @endif
    @empty
        
    @endforelse
</div>