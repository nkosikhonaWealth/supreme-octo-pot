<x-filament::page>
    {{ $this->form }}
    <div class="space-y-4">
        <!-- Tab Buttons -->
        <div class="flex space-x-3 gap-3">
            <x-filament::button wire:click="$set('currentTab', 'Awarded')" class="inline-flex" color="{{ $currentTab === 'Awarded' ? 'primary' : 'gray' }}">
                Youth Messages
            </x-filament::button>

            <x-filament::button wire:click="$set('currentTab', 'Logs')" class="inline-flex" color="{{ $currentTab === 'Logs' ? 'primary' : 'gray' }}">
                Logs
            </x-filament::button>
        </div>

        <!-- Table -->
        {{ $this->table }}
    </div>
</x-filament::page>
