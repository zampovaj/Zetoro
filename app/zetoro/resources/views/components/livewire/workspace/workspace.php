<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component {
    public array $openTabs = [];
    public ?string $activeTabId = null;

    #[On('request-file-open')]
    public function handleFileOpen(string $fileId, string $title): void
    {
        $exists = collect($this->openTabs)->contains('id', $fileId);

        if (!$exists) {
            $this->openTabs[] = [
                'id' => $fileId,
                'title' => $title,
            ];
        }

        $this->activeTabId = $fileId;
    }

    public function closeTab(string $fileId): void
    {
        // filter to remove this id
        $this->openTabs = array_filter($this->openTabs, fn($tab) => $tab['id'] !== $fileId);
        // reindex cause it doent work otherwise
        $this->openTabs = array_values($this->openTabs);
        // switch focus to last available tab or null
        if ($this->activeTabId === $fileId) {
            $this->activeTabId = count($this->openTabs) > 0 ? end($this->openTabs)['id'] : null;
        }
    }

    public function setActiveTab(string $fileId): void
    {
        $this->activeTabId = $fileId;
    }
}; 