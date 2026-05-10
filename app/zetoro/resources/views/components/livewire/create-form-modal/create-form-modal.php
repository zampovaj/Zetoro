<?php

use App\Livewire\Forms\AnnotationForm;
use App\Livewire\Forms\ArticleForm;
use App\Livewire\Forms\FileForm;
use App\Livewire\Forms\FolderForm;
use App\Models\Annotation;
use App\Models\Article;
use App\Models\File;
use App\Models\Folder;
use App\Services\DeleteService;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public ?string $type = null;

    public ?string $parentId = null;

    public ?string $editItemId = null; // for update tracking

    public string $mode = 'create'; // create / edit

    public ArticleForm $articleForm;

    public FolderForm $folderForm;

    public FileForm $fileForm;

    public AnnotationForm $annotationForm;

    #[On('open-create-modal')]
    public function loadCreateModal(string $type, ?string $parentId = null, array $payload = [])
    {
        $this->articleForm->reset();
        $this->folderForm->reset();
        $this->fileForm->reset();
        $this->annotationForm->reset();

        $this->mode = 'create';
        $this->type = $type;
        $this->editItemId = null;
        $this->parentId = $parentId;

        if ($type === 'annotation') {
            $this->annotationForm->fillAnnotation($payload);
        }

        Flux::modal('create-modal')->show();
    }

    #[On('open-edit-modal')]
    public function loadEditModal(string $type, string $itemId)
    {
        $this->mode = 'edit';
        $this->type = $type;
        $this->editItemId = $itemId;
        $this->parentId = null;

        if ($type === 'folder') {
            $this->folderForm->setFolder(Folder::findOrFail($itemId));
        } elseif ($type === 'article') {
            $this->articleForm->setArticle(Article::findOrFail($itemId));
        } elseif ($type === 'file') {
            $this->fileForm->setFile(File::findOrFail($itemId));
        } elseif ($type === 'annotation') {
            $this->annotationForm->setNote(Annotation::findOrFail($itemId));
        }

        Flux::modal('create-modal')->show();
    }

    public function save()
    {
        $item = null;

        if ($this->mode === 'create') {
            $item = match ($this->type) {
                'article' => $this->articleForm->store($this->parentId),
                'folder' => $this->folderForm->store($this->parentId),
                'file' => $this->fileForm->store($this->parentId),
                'annotation' => $this->annotationForm->store($this->parentId),
            };
        } elseif ($this->mode === 'edit') {
            $item = match ($this->type) {
                'article' => $this->articleForm->update(),
                'folder' => $this->folderForm->update(),
                'file' => $this->fileForm->update(),
                'annotation' => $this->annotationForm->update(),
            };
        }

        Flux::modal('create-modal')->close();

        $eventName = $this->mode === 'create' ? 'item-created' : 'item-updated';

        if ($this->type === 'annotation') {
            $this->dispatch('annotation-'.$eventName, annotation: $item);
        } else {
            // passing just id, cause i need to refetch the item WITH relations so it makes no sense to pass around the whole thing
            $this->dispatch($eventName, type: $this->type, itemId: $item->id);
        }
    }

    public function delete(DeleteService $service)
    {
        // this code so ugly i need to refactor asap
        if ($this->type === 'annotation') {
            $fileId = Annotation::findOrFail($this->editItemId)->file_id;
        }

        $idsToRemove = $service->delete($this->type, $this->editItemId);

        if ($this->type === 'annotation') {
            $this->dispatch('annotation-item-deleted', annotationId: $this->editItemId, fileId: $fileId);
        } else {
            $this->dispatch('item-deleted', fileIds: $idsToRemove, itemId: $this->editItemId);
        }

        match ($this->type) {
            'article' => $this->articleForm->reset(),
            'folder' => $this->folderForm->reset(),
            'file' => $this->fileForm->reset(),
            'annotation' => $this->annotationForm->reset(),
            default => null,
        };

        Flux::modal('create-modal')->close();
    }
};
