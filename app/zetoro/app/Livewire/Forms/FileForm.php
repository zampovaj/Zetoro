<?php

namespace App\Livewire\Forms;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FileForm extends Form
{
    public ?File $file = null;

    public string $name = '';
    public ?UploadedFile $uploadFile = null;

    protected function rules()
    {
        return [
            'name' => 'nullable|string|min:1',
            // only required if we not in edit mode
            'uploadFile' => [
                $this->file ? 'nullable' : 'required', 
                'file', 
                'mimes:pdf', 
                'max:51200'
            ],
        ];
    }

    public function setFile(File $file)
    {
        $this->file = $file;
        $this->name = $file->name;
    }

    public function updatedUploadFile()
    {
        if ($this->uploadFile && empty($this->name)) {
            $this->name = pathinfo($this->uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
        }
    }

    public function store(?string $parentId = null): File
    {
        $this->name = $this->name ?: pathinfo($this->uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
        
        $this->validate();

        $folderName = Str::random(16);
        $safeFileName = Str::slug($this->name) . '.pdf';

        $relativePath = $this->uploadFile->storeAs($folderName, $safeFileName, 'pdf_vault');

        $file = File::create([
            'article_id' => $parentId,
            'name' => $this->name,
            'path' => $relativePath,
        ]);

        $this->reset();

        return $file;
    }

    public function update(): File
    {
        $this->validate();

        $this->file->update([
            'name' => $this->name,
        ]);

        $file = $this->file;

        $this->reset();

        return $file;
    }
}
