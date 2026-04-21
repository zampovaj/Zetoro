<?php

use App\Models\File;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;
use Livewire\Component;

new class extends Component
{
    #[Reactive]
    public string $fileId;

    #[Computed()]
    public function file()
    {
        return File::findOrFail($this->fileId);
    }
};
