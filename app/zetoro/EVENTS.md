# Events Cheat Sheet

### ITEMS
- item-created { type, itemId }
- item-updated { type, itemId }
- item-deleted { fileIds } // Array of IDs
- annotation-item-created { annotation } // Full object
- annotation-item-updated { annotation } // Full object
- annotation-item-deleted { annotationId, fileId }
- open-create-modal { type, parentId, payload }
- open-edit-modal { type, itemId }
- pdf-hide-tooltip
- request-file-open { fileId, title }
- pdf-text-selected { payload, mouseX, mouseY, containerId } // payload: { pageNumber, rects[], selectedText }
- pdf-click-away
- pdf-show-tooltip { note, x, y }
- pdf-annotation-clicked { id }
- pdf-annotator-ready
- load-inspector { type, itemId }
- open-annotation { fileId, annotationId, pageNumber }
- request-file-open-scroll { fileId, title, annotationId, pageNumber }



