<x-filament-panels::page>
    <div class="flex justify-end">
        {{ $this->createIklanAction }}
    </div>
    {{ $this->table }}

    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions :actions="$this->getFormActions()" />
    </x-filament-panels::form>
</x-filament-panels::page>
