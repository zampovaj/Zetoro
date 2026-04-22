import * as pdfjsLib from 'pdfjs-dist/legacy/build/pdf.mjs';
import pdfjsWorker from 'pdfjs-dist/legacy/build/pdf.worker.mjs?url';

import * as pdfjsViewer from 'pdfjs-dist/legacy/web/pdf_viewer.mjs';

pdfjsLib.GlobalWorkerOptions.workerSrc = pdfjsWorker;

class PDFAnnotator {
    constructor(pdfUrl, containerId) {
        this.pdfUrl = pdfUrl;
        this.container = document.getElementById(containerId);

        this.init();
        this.bindEvents();
    }

    async init() {
        try {
            const pdf = await pdfjsLib.getDocument(this.pdfUrl).promise;

            for (let i = 1; i < pdf.numPages; i++) {
                await this.renderPage(pdf, i);
            }


        } catch (error) {
            console.error("Error loading PDF:", error);
        }
    }

    async renderPage(pdf, pageNumber) {
        const page = await pdf.getPage(pageNumber);
        const unscaledViewport = page.getViewport({ scale: 1.0 });

        this.container.style.height = '100%';

        let containerWidth = this.container.clientWidth - 32; // 32 for padding
        if (containerWidth <= 0) containerWidth = 736; // fallback width

        const scale = containerWidth / unscaledViewport.width;
        const viewport = page.getViewport({ scale: scale });

        // page
        const pageDiv = document.createElement('div');
        pageDiv.className = 'pdf-page-wrapper relative mx-auto mb-6 bg-white shadow-lg';
        pageDiv.dataset.pageNumber = pageNumber;
        pageDiv.style.width = `${Math.floor(viewport.width)}px`;
        pageDiv.style.height = `${Math.floor(viewport.height)}px`;

        // canvas
        const canvas = document.createElement('canvas');
        canvas.className = 'block';
        const ctx = canvas.getContext('2d');
        const outputScale = window.devicePixelRatio || 1;
        canvas.width = Math.floor(viewport.width * outputScale);
        canvas.height = Math.floor(viewport.height * outputScale);
        canvas.style.width = `${Math.floor(viewport.width)}px`;
        canvas.style.height = `${Math.floor(viewport.height)}px`;
        pageDiv.appendChild(canvas);

        // text layer
        const textLayerDiv = document.createElement('div');
        textLayerDiv.className = 'textLayer absolute top-0 left-0 w-full h-full';
        textLayerDiv.style.setProperty('--scale-factor', scale);
        pageDiv.appendChild(textLayerDiv);

        // anotation layer
        const annotationLayerDiv = document.createElement('div');
        annotationLayerDiv.className = 'custom-annotation-layer absolute top-0 left-0 w-full h-full pointer-events-none';
        pageDiv.appendChild(annotationLayerDiv);

        this.container.appendChild(pageDiv);

        // this.alignLayers();

        const transform = outputScale !== 1 ? [outputScale, 0, 0, outputScale, 0, 0] : null;

        await page.render({
            canvasContext: ctx,
            transform: transform,
            viewport: viewport
        }).promise;

        await this.renderTextLayer(page, textLayerDiv, viewport);
        await this.renderHighlightAnnotations(page, annotationLayerDiv, viewport);
    }

    async renderTextLayer(page, textLayerDiv, viewport) {
        if (pdfjsLib.TextLayer) {
            try {
                const source = typeof page.streamTextContent === 'function'
                    ? page.streamTextContent()
                    : await page.getTextContent();

                const textLayer = new pdfjsLib.TextLayer({
                    textContentSource: source,
                    container: textLayerDiv,
                    viewport: viewport
                });

                await textLayer.render();
                return;
            } catch (err) {
                console.warn("TextLayer initialization failed.", err);
            }
        }

        const TextLayerBuilder = pdfjsViewer.TextLayerBuilder;
        const EventBus = pdfjsViewer.EventBus;

        if (TextLayerBuilder && EventBus) {
            const eventBus = new EventBus();
            const textLayerBuilder = new TextLayerBuilder({
                textLayerDiv: textLayerDiv,
                pageIndex: page.pageIndex,
                viewport: viewport,
                eventBus: eventBus
            });

            if (typeof textLayerBuilder.setTextContentSource === 'function') {
                textLayerBuilder.setTextContentSource(page);
            } else {
                textLayerBuilder.textContentSource = page;
            }

            textLayerBuilder.render(viewport);
        }
    }

    // alignLayers() {
    //     const canvasLeft = this.canvas.offsetLeft;
    //     const width = Math.floor(this.viewport.width);
    //     const height = Math.floor(this.viewport.height);

    //     [this.textLayerDiv, this.annotationLayerDiv].forEach(layer => {
    //         layer.style.width = `${width}px`;
    //         layer.style.height = `${height}px`;
    //         layer.style.left = `${canvasLeft}px`;
    //         layer.style.top = '0px';
    //     });

    //     this.textLayerDiv.style.setProperty('--scale-factor', this.scale);
    // }

    async renderHighlightAnnotations(page, annotationLayerDiv, viewport) {
        const annotations = await page.getAnnotations();

        annotations.forEach(annotation => {
            if (annotation.subtype === "Highlight") {
                // if quadpoints exists -> multiline highlight
                // quadpoints => flat array of numbers
                // each 8 elements are one shape, because 4 points x 2 coordinates (x, y) each
                // they are stored in a specific order:
                // bottom-left (x, y)
                // bottom-right (x, y)
                // top-left (x, y)
                // top-right (x, y)
                if (annotation.quadPoints && annotation.quadPoints.length > 0) {
                    for (let i = 0; i < annotation.quadPoints.length; i += 8) {
                        const quad = annotation.quadPoints.slice(i, i + 8);

                        // transforming quad points into a viewport rectangle
                        const rect = [
                            quad[0], // x min - bottom-left (x)
                            quad[1], // y min - bottom-left (y)
                            quad[2], // x max - top-right (x)
                            quad[5]  // y max - top-left (y)
                        ];

                        const vRect = viewport.convertToViewportRectangle(rect);
                        this.addHighlightToDOM(vRect, annotationLayerDiv);
                    }
                } else {
                    const vRect = viewport.convertToViewportRectangle(annotation.rect);
                    this.addHighlightToDOM(vRect, annotationLayerDiv);
                }
            }
        });
    }

    addHighlightToDOM(vRect, annotationLayerDiv) {
        const highlight = document.createElement("div");
        highlight.style.position = "absolute";
        highlight.style.left = `${Math.min(vRect[0], vRect[2])}px`;
        highlight.style.top = `${Math.min(vRect[1], vRect[3])}px`;
        highlight.style.width = `${Math.abs(vRect[0] - vRect[2])}px`;
        highlight.style.height = `${Math.abs(vRect[1] - vRect[3])}px`;
        highlight.style.backgroundColor = "rgba(255, 255, 0, 0.5)";
        highlight.style.pointerEvents = "none";
        annotationLayerDiv.appendChild(highlight);
    }

    bindEvents() {
        this.container.addEventListener('mouseup', (e) => {
            const sel = window.getSelection();
            if (!sel.toString() || sel.rangeCount === 0) return;

            const range = sel.getRangeAt(0);

            // page
            let node = range.commonAncestorContainer;
            if (node.nodeType === 3) node = node.parentNode;
            const pageContainer = node.closest('.pdf-page-wrapper');
            if (!pageContainer) return;

            // page specific annotation layer
            const annotationLayerDiv = pageContainer.querySelector('.custom-annotation-layer');
            const containerRect = annotationLayerDiv.getBoundingClientRect();

            // get all rectangles and treat them as an array
            const rawRects = Array.from(range.getClientRects());

            // turn them into lines
            const mergedRects = this.mergeRectangles(rawRects);

            // create an array of rectangles
            const finalRects = mergedRects.map(rect => {
                const left = rect.left - containerRect.left;
                const top = rect.top - containerRect.top;

                return {
                    x_min: left,
                    y_min: top,
                    x_max: left + rect.width,
                    y_max: top + rect.height
                };
            });

            console.log(pageContainer.dataset.pageNumber);

            const payload = {
                page: parseInt(pageContainer.dataset.pageNumber || 1),
                rectangles: finalRects
            }

            window.dispatchEvent(new CustomEvent('pdf-text-selected', {
                detail: {
                    payload: payload,
                    mouseX: e.clientX,
                    mouseY: e.clientY
                }
            }));

            // hide dropdown
            this.container.addEventListener("mousedown", () => {
                if (window.getSelection().toString() === "") {
                    window.dispatchEvent(new CustomEvent('pdf-click-away'));
                }
            });
        })
    }

    // bindEvents() {
    //     this.container.addEventListener("mouseup", () => {
    //         const sel = window.getSelection();
    //         if (!sel.toString() || sel.rangeCount === 0) return;

    //         const range = sel.getRangeAt(0);

    //         // page
    //         let node = range.commonAncestorContainer;
    //         if (node.nodeType === 3) node = node.parentNode;
    //         const pageContainer = node.closest('.pdf-page-wrapper');
    //         if (!pageContainer) return;

    //         // page specific annotation layer
    //         const annotationLayerDiv = pageContainer.querySelector('.custom-annotation-layer');
    //         const containerRect = annotationLayerDiv.getBoundingClientRect();

    //         // get all rectangles and treat them as an array
    //         const rawRects = Array.from(range.getClientRects());

    //         // turn them into lines
    //         const mergedRects = this.mergeRectangles(rawRects);

    //         const highlightGroup = document.createElement("div");
    //         highlightGroup.className = "highlight-group";

    //         mergedRects.forEach(rect => {
    //             const div = document.createElement("div");
    //             div.style.position = "absolute";
    //             div.style.left = `${rect.left - containerRect.left}px`;
    //             div.style.top = `${rect.top - containerRect.top}px`;
    //             div.style.width = `${rect.width}px`;
    //             div.style.height = `${rect.height}px`;
    //             div.style.background = "rgba(255, 255, 0, 0.4)";
    //             div.style.pointerEvents = "none";
    //             highlightGroup.appendChild(div);
    //         });

    //         annotationLayerDiv.appendChild(highlightGroup);
    //     });
    // }

    // need to have this one to remove duplications and create nice lines
    mergeRectangles(rects) {
        if (rects.length === 0) return [];

        // sort by top position, then by left
        const sorted = rects.map(r => ({
            top: r.top,
            bottom: r.bottom,
            left: r.left,
            right: r.right,
            width: r.width,
            height: r.height
        })).sort((a, b) => a.top - b.top || a.left - b.left);

        const merged = [];
        let current = sorted[0];

        for (let i = 1; i < sorted.length; i++) {
            const next = sorted[i];

            // rectangles are not precise
            // check for same-line or ovewrlapping rectangles with tolerance
            const isSameLine = Math.abs(next.top - current.top) < 2;
            const overlaps = next.left <= current.right + 1;

            if (isSameLine && overlaps) {
                // merge overlapping
                current.right = Math.max(current.right, next.right);
                current.width = current.right - current.left;
            } else {
                merged.push(current);
                current = next;
            }
        }
        merged.push(current);
        return merged;
    }
}

window.PDFAnnotator = PDFAnnotator;
window.dispatchEvent(new CustomEvent('pdf-annotator-ready'));
