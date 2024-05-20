<x-filament-panels::page>

    {{ $this->form }}

    <x-filament::button wire:click="submit" type="submit" color="primary" style="width: 100px;">
        {{ __('Create') }}
    </x-filament::button>
</x-filament-panels::page>
