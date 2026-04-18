<?php

namespace App\Livewire\Forms;

use App\Models\File;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FileForm extends Form
{
    public ?File $file = null;

    #[Validate('nullable|string|min:1')]
    public string $name = 'article';

    #[Validate('required|string|min:1')]
    public string $path = '';

    public function setFile(File $file)
    {
        $this->file = $file;
        $this->name = $file->name;
        $this->path = $file->path;
    }

    public function store(?string $parentId = null): File
    {
        $this->validate();

        $file = File::create([
            'article_id' => $parentId,
            'name' => $this->name,
            'path' => $this->path,
        ]);

        $this->reset();

        return $file;
    }

    public function update(): File
    {
        $this->validate();

        $this->file->update([
            'name' => $this->name,
            'path' => $this->path,
        ]);

        $file = $this->file;

        $this->reset();

        return $file;
    }
}
