<?php

use Livewire\Component;
use App\Models\Article;
use App\Models\Folder;
use App\Models\File;
use Illuminate\Database\Eloquent\Collection;
new class extends Component {
    // In the future, this will be loaded from the DB
    // public array $articles = [['id' => '01HQ...', 'title' => 'Quantum Entanglement', 'file_id' => 'file_1'], ['id' => '01HR...', 'title' => 'Dual EC Backdoor', 'file_id' => 'file_2']];

    public Collection $allFolders;
    public Collection $rootFolders;
    public Collection $orphanArticles;

    public function mount(): void {
        $this->loadData();
    }

    private function loadData(): void {
        $this->allFolders = Folder::with('articles.files')->get();
        $this->rootFolders = $this->allFolders->whereNull('parent_id');
        $this->orphanArticles = Article::doesntHave('folders')->with('files')->get();
    }

    public function triggerEdit(string $type, $itemId): void {}

    public function triggerCreate(string $type, $itemId): void {}
    
    public function triggerDelete(string $type, $itemId): void {}

    public function openArticle(string $fileId, string $title): void
    {
        // We do not manage the workspace here. We just dispatch an event
        // that the Workspace component will listen for.
        $this->dispatch('request-file-open', fileId: $fileId, title: $title);
    }
}; ?>

<div class="flex flex-col h-full">
    <div
        class="p-3 border-b border-gray-700 flex justify-between items-center text-sm font-semibold uppercase tracking-wider text-gray-400">
        <span>Explorer</span>
        <button class="hover:text-white" title="New Folder">+</button>
    </div>

    <div class="flex-1 overflow-y-auto p-2">
        <ul class="space-y-1">
            @foreach ($articles as $article)
                <li>
                    <button wire:dblclick="openArticle('{{ $article['file_id'] }}', '{{ $article['title'] }}')"
                        class="w-full text-left px-2 py-1.5 text-sm rounded hover:bg-gray-700 focus:outline-none transition-colors truncate">
                        📄 {{ $article['title'] }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>
</div>
