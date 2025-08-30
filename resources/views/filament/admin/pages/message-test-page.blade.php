<x-filament::page>

        {{ $this->form }}

    <div class="space-y-4">
        <div class="flex space-x-3 gap-3">
            <x-filament::button wire:click="$set('currentTab', 'Test')" class="inline-flex" color="{{ $currentTab === 'Test' ? 'primary' : 'gray' }}">
                Test Emails
            </x-filament::button>

            <x-filament::button wire:click="$set('currentTab', 'TestLogs')" class="inline-flex" color="{{ $currentTab === 'TestLogs' ? 'primary' : 'gray' }}">
                Test Logs
            </x-filament::button>
        </div>

        {{ $this->table }}
    </div>
</x-filament::page>
