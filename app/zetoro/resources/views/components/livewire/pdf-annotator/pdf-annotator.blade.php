@php
    $containerId = 'pdf-container-' . $fileId;
@endphp

<div data-annotations="{{ json_encode($this->annotations) }}" x-data="{
    annotator: null,

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

    updateAnnotation(annotation) {
        if (annotation.file_id != '{{ $this->fileId }}') return;

        this.annotator.removeHighlightsFromDOM(annotation.id);
        this.annotator.addNewAnnotation(annotation);
    }

}" x-init="initPdf()"
    class="w-full h-full relative" 
    @annotation-item-created.window="if ($event.detail.annotation.file_id === '{{ $this->fileId }}') annotator.addNewAnnotation($event.detail.annotation)"
    @annotation-item-updated.window="updateAnnotation($event.detail.annotation)"
    @pdf-annotation-clicked.window="$wire.triggerEditHighlight($event.detail.id)"
    @pdf-annotation-deleted.window="annotator.removeHighlightsFromDOM($event.detail.id)">

    <div wire:ignore id="{{ $containerId }}"
        class="relative w-full max-w-3xl mx-auto bg-gray-100 overflow-y-auto overflow-x-hidden p-4 h-[100vh]">
        {{-- pages will get injected by javascript here --}}
    </div>

    <x-pdf.highlight-dropdown :containerId="$containerId"></x-pdf.highlight-dropdown>

    <x-pdf.tooltip></x-pdf.tooltip>

</div>
