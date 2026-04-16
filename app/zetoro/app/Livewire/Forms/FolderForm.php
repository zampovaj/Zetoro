<?php

namespace App\Livewire\Forms;

use App\Models\Folder;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FolderForm extends Form
{
    #[Validate('required|string|min:1')]
    public string $name = '';

    public function store(?string $parentId = null)
    {
        $folder = Folder::create([
            'parent_id' => $parentId,
            'name' => $this->name,
        ]);

        $folder->store();
    }
}
