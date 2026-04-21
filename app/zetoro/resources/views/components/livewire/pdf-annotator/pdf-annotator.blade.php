@php
    $containerId = 'pdf-container-' . $fileId;
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

        this.annotator = new window.PDFAnnotator(url,'{{ $containerId }}');
    }
}" x-init="initPdf();" class="w-full h-full">
    <div wire:ignore id="{{ $containerId }}" class="relative w-full max-w-3xl mx-auto bg-gray-100 overflow-y-auto overflow-x-hidden p-4 h-[100vh]">
        {{-- pages will gte injected by javascript here --}}
    </div>
</div>
