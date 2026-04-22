@php
    $containerId = 'pdf-container-' . $fileId;
@endphp

<div x-data="{
    annotator: null,
    showMenu: false,
    menuX: 0,
    menuY: 0,
    selectionPayload: null,

    initPdf() {
        if (!window.PDFAnnotator) {
            window.addEventListener('pdf-annotator-ready', () => {
                this.annotator = new window.PDFAnnotator('{{ route('files.pdf', $this->fileId) }}', '{{ $containerId }}');
            }, { once: true });
        } else {
            this.annotator = new window.PDFAnnotator('{{ route('files.pdf', $this->fileId) }}', '{{ $containerId }}');
        }
    },

    handleSelection(event) {
        this.selectionPayload = event.detail.payload;
        this.menuX = event.detail.mouseX + 5;
        this.menuY = event.detail.mouseY + 10;
        this.showMenu = true;
    },

    saveHighlight() {
        this.showMenu = false;
        this.$wire.saveHighlight(this.selectionPayload);
        window.getSelection().removeAllRanges();
    },

    openNoteModal() {
        this.showMenu = false;
        this.$wire.triggerCreateNote(this.selectionPayload);
        window.getSelection().removeAllRanges();
    }
}" x-init="initPdf()" @pdf-text-selected.window="handleSelection($event)"
    @pdf-click-away.window="showMenu = false" class="w-full h-full relative">

    <div wire:ignore id="{{ $containerId }}"
        class="relative w-full max-w-3xl mx-auto bg-gray-100 overflow-y-auto overflow-x-hidden p-4 h-[100vh]">
        {{-- pages will get injected by javascript here --}}
    </div>

    <div x-show="showMenu" x-transition.opacity.duration.100ms @click.away="showMenu = false"
        class="fixed z-50 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-xl rounded-md py-1 min-w-[150px]"
        x-bind:style="`top: ${menuY}px; left: ${menuX}px;`" style="display: none;">

        <button @click="saveHighlight()"
            class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center gap-2">
            <div class="size-3 rounded-full bg-yellow-400 border border-yellow-500"></div> Highlight
        </button>

        <button @click="openNoteModal()"
            class="w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 flex items-center gap-2">
            <flux:icon.pencil-square class="size-4 text-blue-500" /> Add Note
        </button>
    </div>
</div>
