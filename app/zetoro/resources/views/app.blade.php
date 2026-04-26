<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zetoro</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/pdfAnnotator.js'])
    @fluxAppearance
</head>

<body class="min-h-screen bg-white dark:bg-zinc-900 overflow-hidden">

    <flux:sidebar sticky stashable class="w-90 bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
        <livewire:livewire.file-explorer></livewire:livewire.file-explorer>
    </flux:sidebar>

    <flux:main class="p-0 h-screen overflow-hidden relative">
        
        <div class="flex h-full w-full">
            
            <div class="flex-1 flex flex-col min-w-0 pr-80">
                <livewire:livewire.workspace></livewire:livewire.workspace>
                <livewire:livewire.create-form-modal></livewire:livewire.create-form-modal>
            </div>

            <livewire:livewire.sidebar-inspector></livewire:livewire.sidebar-inspector>

        </div>
    </flux:main>

    @livewireScripts
    @fluxScripts
</body>
</html>