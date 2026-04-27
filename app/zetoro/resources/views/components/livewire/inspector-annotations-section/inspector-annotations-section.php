<?php

use App\Models\Annotation;
use App\Models\File;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?File $file;

    #[On('annotation-item-deleted')]
    public function handleAnnotationDelete(string $fileId, string $annotationId)
    {
        $this->refresh($fileId);
    }

    #[On('annotation-item-created')]
    #[On('annotation-item-updated')]
    public function handleAnnotationUpdate($annotation)
    {
        // livewire passes the object as an array
        $this->refresh($annotation['file_id'] ?? '');
    }

    public function refresh(string $fileId)
    {
        if ($this->file->id != $fileId) {
            return;
        }

        $this->file->load('annotations');
    }

    public function openAnnotation(string $annotationId, int $pageNumber): void
    {
        $this->dispatch('request-file-open-scroll', fileId: $this->file->id, title: $this->file->name, annotationId: $annotationId, pageNumber: $pageNumber);
    }
};
