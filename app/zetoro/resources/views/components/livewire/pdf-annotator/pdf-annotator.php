<?php

use App\Livewire\Forms\AnnotationForm;
use App\Models\File;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

new class extends Component
{
    public AnnotationForm $annotationForm;

    #[Reactive]
    public string $fileId;

    #[Computed()]
    public function file()
    {
        return File::findOrFail($this->fileId);
    }

    public function saveHighlight(array $payload)
    {
        $this->annotationForm->fillAnnotation($payload);
        $annotation = $this->annotationForm->store($this->fileId);

        $this->dispatch('annotation-created', annotation: $annotation);
    }

    public function triggerCreateNote(array $payload)
    {
        $this->dispatch('open-create-modal', type: 'annotation', parentId: $this->fileId, payload: $payload);
    }
};
