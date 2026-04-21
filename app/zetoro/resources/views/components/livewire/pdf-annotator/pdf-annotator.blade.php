@php
    $containerId = 'pdf-container-' . $fileId;
    $canvasId = 'pdf-canvas-' . $fileId;
    $textLayerId = 'text-layer-' . $fileId;
    $annotationLayerId = 'annotation-layer-' . $fileId;
@endphp

<div x-data="{
    initPdf() {
        if (!window.PDFAnnotator) {
            window.addEventListener('pdf-annotator-ready', () => {
                this.initPdf();
            }, { once: true });
            return;
        }

        const url = '{{ route('files.pdf', $this->fileId) }}';

        this.annotator = new window.PDFAnnotator(
            url,
            '{{ $containerId }}',
            '{{ $canvasId }}',
            '{{ $textLayerId }}',
            '{{ $annotationLayerId }}'
        );
    }
}" x-init="initPdf();" class="w-full h-full">
    <div wire:ignore id="{{ $containerId }}" class="relative w-full max-w-3xl mx-auto bg-gray-100 overflow-hidden">
        <canvas id="{{ $canvasId }}" class="bg-white block mx-auto"></canvas>
        <div id="{{ $textLayerId }}" class="textLayer absolute top-0 left-0 w-full h-full"></div>
        <div id="{{ $annotationLayerId }}" class="absolute top-0 left-0 w-full h-full pointer-events-none"></div>
    </div>
</div>
