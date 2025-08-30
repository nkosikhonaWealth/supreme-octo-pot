<x-filament::page>
    <div class="mb-4 flex items-center gap-4">
        <label for="month" class="text-sm font-medium text-gray-700">Filter by Month:</label>
        <input type="month"
               wire:model.live="selectedMonth"
               id="month"
               class="border border-gray-300 rounded-md p-2"
        />
    </div>
    <x-filament-widgets::widgets
        :widgets="$this->getWidgets()"
        :columns="12"
    />
</x-filament::page>
