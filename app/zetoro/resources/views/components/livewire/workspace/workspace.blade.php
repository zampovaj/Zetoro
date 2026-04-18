<div class="flex flex-col h-full">
    <div class="flex bg-gray-900 overflow-x-auto border-b border-gray-700 hide-scrollbar">
        @if (empty($openTabs))
            <div class="px-4 py-2 text-sm text-gray-500 italic">No files open</div>
        @endif

        @foreach ($openTabs as $tab)
            <div
                class="group flex items-center min-w-max border-r border-gray-700 cursor-pointer select-none
                       {{ $activeTabId === $tab['id'] ? 'bg-gray-800 text-white border-t-2 border-t-blue-500' : 'bg-gray-900 text-gray-400 hover:bg-gray-800' }}">
                <div wire:click="setActiveTab('{{ $tab['id'] }}')" class="px-4 py-2 text-sm">
                    {{ $tab['title'] }}
                </div>

                <button wire:click.stop="closeTab('{{ $tab['id'] }}')"
                    class="px-2 py-2 text-gray-500 opacity-0 group-hover:opacity-100 hover:text-red-400 transition-opacity focus:outline-none">
                    ✕
                </button>
            </div>
        @endforeach
    </div>

    <div class="flex-1 bg-gray-800 overflow-hidden relative">
        @if ($activeTabId)
            <div class="flex items-center justify-center h-full text-gray-400 flex-col">
                <span class="text-4xl mb-4">📄</span>
                <p>PDF Viewer initialized for File ID: <strong class="text-white">{{ $activeTabId }}</strong></p>
            </div>
        @else
            <div class="flex items-center justify-center h-full text-gray-600">
                <p>Workspace</p>
            </div>
        @endif
    </div>
</div>