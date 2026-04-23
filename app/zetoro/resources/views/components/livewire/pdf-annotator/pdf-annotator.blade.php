@php
    $containerId = 'pdf-container-' . $fileId;
@endphp

<div data-annotations="{{ json_encode($this->annotations) }}" x-data="{
    annotator: null,
    showMenu: false,
    menuX: 0,
    menuY: 0,
    selectionPayload: null,

    showToolTip: false,
    tooltipText: '',
    tooltipX: 0,
    tooltipY: 0,

    initPdf() {
        const dbAnnotations = JSON.parse(this.$el.dataset.annotations || '[]');

        if (!window.PDFAnnotator) {
            window.addEventListener('pdf-annotator-ready', () => {
                this.annotator = new window.PDFAnnotator(
                    '{{ route('files.pdf', $this->fileId) }}',
                    '{{ $containerId }}',
                    dbAnnotations
                );
            }, { once: true });
        } else {
            this.annotator = new window.PDFAnnotator(
                '{{ route('files.pdf', $this->fileId) }}',
                '{{ $containerId }}',
                dbAnnotations
            );
        }
    },

    handleSelection(event) {
        if (event.detail.containerId !== '{{ $containerId }}') return;

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
    },

    displayToolTip(event) {
        showToolTip = true;
        tooltipText = event.detail.note;
        tooltipX = event.detail.x;
        tooltipY = event.detail.y;
    },

    updateAnnotation(annotation) {        
        if (annotation.file_id != '{{ $this->fileId }}') return;
        
        this.annotator.removeHighlightsFromDOM(annotation.id);
        this.annotator.drawDatabaseAnnotation(annotation);
    }

}" x-init="initPdf()"
    @pdf-text-selected.window="handleSelection($event)" @pdf-click-away.window="showMenu = false"
    class="w-full h-full relative"
    @annotation-item-created.window="if ($event.detail.annotation.file_id === '{{ $this->fileId }}') annotator.drawDatabaseAnnotation($event.detail.annotation)"
    @annotation-item-updated.window="updateAnnotation($event.detail.annotation)"
    @pdf-annotation-clicked.window="$wire.triggerEditHighlight($event.detail.id)"
    @pdf-show-tooltip.window="displayToolTip($event)"
    @pdf-hide-tooltip.window="showToolTip = false">

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

    <div x-show="showToolTip" x-transition.opacity.duration.100ms
        class="fixed z-50 px-3 py-2 text-sm font-medium text-white bg-zinc-900 dark:bg-zinc-700 rounded-lg shadow-lg pointer-events-none max-w-xs whitespace-pre-wrap"
        x-bind:style="`top: ${tooltipY + 15}px; left: ${tooltipX + 15}px;`" x-text="tooltipText" style="display: none;">
    </div>
</div>
