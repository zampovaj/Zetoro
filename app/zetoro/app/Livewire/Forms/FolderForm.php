<?php

namespace App\Livewire\Forms;

use App\Models\Folder;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FolderForm extends Form
{
    public ?Folder $folder = null;

    #[Validate('required|string|min:1')]
    public string $name = '';

    public function setFolder(Folder $folder)
    {
        $this->folder = $folder;
        $this->name = $folder->name;
    }

    public function store(?string $parentId = null): Folder
    {
        $parentId = $parentId ?: null;

        $this->validate();

        $folder = Folder::create([
            'parent_id' => $parentId,
            'name' => $this->name,
        ]);

        $this->reset();

        return $folder;
    }

    public function update(): Folder
    {
        $this->validate();

        $this->folder->update([
            'name' => $this->name,
        ]);

        $folder = $this->folder;

        $this->reset();

        return $folder;
    }
}
