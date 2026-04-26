<?php

use App\Models\Annotation;
use Illuminate\Support\Collection;
use Livewire\Component;
use App\Models\File;

new class extends Component
{
    public ?File $file;

    public function openAnnotation(string $annotationId, int $pageNumber): void
    {
        $this->dispatch('request-file-open-scroll', fileId: $this->file->id, title: $this->file->name, annotationId: $annotationId, pageNumber: $pageNumber);
    }
};
