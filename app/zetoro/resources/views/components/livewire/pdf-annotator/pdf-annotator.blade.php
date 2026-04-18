<div>
    <div id="pdf-container" class="relative w-full max-w-3xl mx-auto bg-gray-100 overflow-hidden">
        <canvas id="pdf-canvas" class="bg-white block mx-auto"></canvas>
        <div id="text-layer" class="textLayer absolute top-0 left-0 w-full h-full"></div>
        <div id="annotation-layer" class="absolute top-0 left-0 w-full h-full pointer-events-none"></div>
    </div>

    @vite(['resources/css/app.css', 'resources/js/pdfAnnotator.js'])
</div>