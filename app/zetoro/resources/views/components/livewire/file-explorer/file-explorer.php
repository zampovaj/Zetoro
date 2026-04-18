<?php

use App\Models\Article;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public Collection $allFolders;

    public Collection $rootFolders;

    public Collection $orphanArticles;

    public function mount(): void
    {
        $this->loadData();
    }

    // public function boot(): void
    // {
    //     $this->mockData();
    // }

    private function loadData(): void
    {
        $this->allFolders = Folder::with('articles.files')->get();
        $this->rootFolders = $this->allFolders->whereNull('parent_id');
        $this->orphanArticles = Article::doesntHave('folders')->with('files')->get();
    }

    private function mockData(): void
    {
        // ---------------------------------------------------------
        // 1. MOCK THE FILES
        // ---------------------------------------------------------
        $file1 = (new File)->forceFill(['id' => 'f1', 'name' => 'quantum_entanglement.pdf']);
        $file2 = (new File)->forceFill(['id' => 'f2', 'name' => 'dual_ec_backdoor.pdf']);
        $file3 = (new File)->forceFill(['id' => 'f3', 'name' => 'supplementary_data.csv']);
        $file4 = (new File)->forceFill(['id' => 'f4', 'name' => 'laravel_architecture.pdf']);

        // ---------------------------------------------------------
        // 2. MOCK THE ARTICLES & ATTACH FILES
        // ---------------------------------------------------------
        $article1 = (new Article)->forceFill(['id' => 'a1', 'metadata' => ['title' => 'Quantum Entanglement']]);
        $article1->setRelation('files', new Collection([$file1]));

        $article2 = (new Article)->forceFill(['id' => 'a2', 'metadata' => ['title' => 'Dual EC: A Standardized Back Door']]);
        $article2->setRelation('files', new Collection([$file2, $file3]));

        $article3 = (new Article)->forceFill(['id' => 'a3', 'metadata' => ['title' => 'The Laravel Lifecycle']]);
        $article3->setRelation('files', new Collection([$file4]));

        // ---------------------------------------------------------
        // 3. MOCK THE FOLDERS & ATTACH ARTICLES
        // ---------------------------------------------------------
        $folder1 = (new Folder)->forceFill(['id' => 'dir1', 'name' => 'Physics', 'parent_id' => null]);
        $folder1->setRelation('articles', new Collection([$article1]));

        $folder2 = (new Folder)->forceFill(['id' => 'dir2', 'name' => 'Computer Science', 'parent_id' => null]);
        $folder2->setRelation('articles', new Collection);

        $folder3 = (new Folder)->forceFill(['id' => 'dir3', 'name' => 'Cryptography', 'parent_id' => 'dir2']);
        $folder3->setRelation('articles', new Collection([$article2]));

        // ---------------------------------------------------------
        // 4. POPULATE THE COMPONENT STATE
        // ---------------------------------------------------------
        $this->allFolders = new Collection([$folder1, $folder2, $folder3]);
        $this->rootFolders = $this->allFolders->whereNull('parent_id');
        $this->orphanArticles = new Collection([$article3]);
    }

    public function triggerEdit(string $type, $itemId): void {}

    public function triggerCreate(string $type, $itemId): void
    {
        $this->dispatch('open-create-modal', type: $type, parentId: $itemId);
    }

    public function triggerDelete(string $type, $itemId): void
    {
        match ($type) {
            'folder' => Folder::destroy($itemId),
            'article' => Article::destroy($itemId),
            'file' => File::destroy($itemId),
        };
    }

    public function openFile(string $fileId, string $title): void
    {
        $this->dispatch('request-file-open', fileId: $fileId, title: $title);
    }

    #[On('item-created')]
    public function handleItemCreated(string $type, string $itemId)
    {
        if ($type === 'folder') {
            $newFolder = Folder::with('articles.files')->find($itemId);

            $this->allFolders->push($newFolder);

            if (is_null($newFolder->parent_id)) {
                $this->rootFolders->push($newFolder);
            }
        } elseif ($type === 'article') {
            $newArticle = Article::with('files')->find($itemId);

            if (is_null($newArticle->parent_id)) {
                $this->orphanArticles->push($newArticle);
            } else {
                // its actually cheaper to reload then search inside the tree
                $this->loadData();
            }
        } elseif ($type === 'file') {
            $this->loadData();
        } else {
            $this->loadData();
        }
    }
};
