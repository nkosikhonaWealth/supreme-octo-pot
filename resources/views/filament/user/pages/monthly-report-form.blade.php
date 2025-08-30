<x-filament::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <div class="mt-4">
            @foreach ($this->getFormActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </form>
</x-filament::page>
