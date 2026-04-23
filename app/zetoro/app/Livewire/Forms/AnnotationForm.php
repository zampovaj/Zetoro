<?php

namespace App\Livewire\Forms;

use App\Models\Annotation;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AnnotationForm extends Form
{
    public ?Annotation $annotation;

    #[Validate('required|array')]
    public array $rectangles = [];

    #[Validate('required|integer|min:1')]
    public int $page = 1;

    #[Validate('nullable|string')]
    public string $highlightColor = '';

    #[Validate('nullable|string')]
    public ?string $note = null;

    public function setNote(Annotation $annotation)
    {
        $this->annotation = $annotation;
        $this->note = $annotation->note;
        $this->rectangles = $annotation->rectangles;
        $this->page = $annotation->page;
        $this->highlightColor = $annotation->highlight_color;
    }

    public function fillAnnotation(array $data)
    {
        $this->rectangles = $data['rectangles'];
        $this->page = $data['page'];
    }

    public function store(string $fileId): Annotation
    {
        $this->validate();

        if (empty($this->highlightColor) || $this->highlightColor === null) {
            $this->highlightColor = (empty($this->note) || $this->note === null) ? '#FFFF0080' : '#00bbff80';
        }

        $annotation = Annotation::create([
            'file_id' => $fileId,
            'rectangles' => $this->rectangles,
            'page' => $this->page,
            'highlight_color' => $this->highlightColor,
            'note' => $this->note,
        ]);

        $this->reset();

        return $annotation;
    }

    public function update(): Annotation
    {
        $this->validate();

        $this->highlightColor = (empty($this->note) || $this->note === null) ? '#FFFF0080' : '#00bbff80';

        $this->annotation->update([
            'note' => $this->note,
            'highlight_color' => $this->highlightColor,
        ]);

        $annotation = $this->annotation;

        $this->reset();

        return $annotation;
    }
}
