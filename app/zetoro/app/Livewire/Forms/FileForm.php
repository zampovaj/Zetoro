<?php

namespace App\Livewire\Forms;

use App\Models\File;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FileForm extends Form
{
    #[Validate('nullable|string|min:1')]
    public string $name = 'article';

    #[Validate('required|string|min:1')]
    public string $path = '';

    public function store(?string $parentId = null)
    {
        $this->validate();

        $file = File::create([
            'article_id' => $parentId,
            'name' => $this->name,
            'path' => $this->path,
        ]);

        $this->reset();
    }
}
