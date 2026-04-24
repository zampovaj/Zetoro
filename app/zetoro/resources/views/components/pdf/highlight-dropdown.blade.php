@prop(['containerId'])

<div x-data="{
    show: false,
    xCoordinate: 0,
    yCoordinate: 0,
    selectionPayload: null,

    display(event) {
        if (event.detail.containerId !== '{{ $containerId }}') return;

        this.selectionPayload = event.detail.payload;
        this.xCoordinate = event.detail.mouseX + 5;
        this.yCoordinate = event.detail.mouseY + 10;
        this.show = true;
    },

    saveHighlight() {
        this.show = false;
        this.$wire.saveHighlight(this.selectionPayload);
        window.getSelection().removeAllRanges();
    },

    openNoteModal() {
        this.show = false;
        this.$wire.triggerCreateNote(this.selectionPayload);
        window.getSelection().removeAllRanges();
    },

    hide() {
        this.show = false;
    }

}"
    @pdf-text-selected.window="display($event)"
    @pdf-click-away.window="hide()"
    
    x-show="show" x-transition.opacity.duration.100ms @click.away="show = false"
    class="fixed z-50 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-xl rounded-md py-1 min-w-[150px]"
    x-bind:style="`top: ${yCoordinate}px; left: ${xCoordinate}px;`" style="display: none;">

    <button @click="saveHighlight()"
        class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center gap-2">
        <div class="size-3 rounded-full bg-yellow-400 border border-yellow-500"></div> Highlight
    </button>

    <button @click="openNoteModal()"
        class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center gap-2">
        <flux:icon.pencil-square class="size-4 text-blue-500" /> Add Note
    </button>
</div>
