<div class="flex flex-col h-full">
    <div
        class="py-3 border-b border-gray-700 flex justify-between items-center text-sm font-semibold uppercase tracking-wider text-gray-400">
        <span>Explorer</span>

        <x-create-dropdown :itemId="null" />

    </div>

    <div class="flex-1 overflow-y-auto py-2">
        <ul class="space-y-1">

            @foreach ($rootFolders as $rootFolder)
                <x-explorer-node type="folder" :item="$rootFolder" :allFolders="$this->allFolders" :level="0" />
            @endforeach

            @foreach ($orphanArticles as $article)
                <x-explorer-node type="article" :item="$article" :allFolders="$this->allFolders" :level="0" />
            @endforeach
        </ul>
    </div>
</div>