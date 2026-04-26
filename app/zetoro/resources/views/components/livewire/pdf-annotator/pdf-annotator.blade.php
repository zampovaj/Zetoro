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
    },

    handleScrollRequest(fileId, annotationId, pageNumber) {
        const needsFileSwitch = fileId != '{{ $this->fileId }}';
        const delay = needsFileSwitch ? 500 : 100;

        setTimeout(() => {
            if (fileId != '{{ $this->fileId }}') return;
            this.annotator.scrollToAnnotation(annotationId, pageNumber);
        }, delay);
    }

}" x-init="initPdf()"
    class="w-full h-full relative"
    @annotation-item-created.window="if ($event.detail.annotation.file_id === '{{ $this->fileId }}') annotator.addNewAnnotation($event.detail.annotation)"
    @annotation-item-updated.window="updateAnnotation($event.detail.annotation)"
    @pdf-annotation-clicked.window="$wire.triggerEditHighlight($event.detail.id)"
    @pdf-annotation-deleted.window="annotator.removeHighlightsFromDOM($event.detail.id)"
    @open-annotation.window="handleScrollRequest($event.detail.fileId, $event.detail.annotationId, $event.detail.pageNumber)">

    <div wire:ignore id="{{ $containerId }}"
        class="relative w-full max-w-3xl mx-auto bg-gray-100 overflow-y-auto overflow-x-hidden p-4 h-[100vh]">
        {{-- pages will get injected by javascript here --}}
    </div>

    <x-pdf.highlight-dropdown :containerId="$containerId"></x-pdf.highlight-dropdown>

    <x-pdf.tooltip></x-pdf.tooltip>

</div>
