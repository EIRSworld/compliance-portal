<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <hr
        class="my-12 h-0.5 border-t-0 bg-neutral-100 opacity-100 dark:opacity-50" />

    <div class="flex flex-col ">
        <a
            style="height: 50px; padding: 15px; border: 1px solid #ccc"
            href="{{ url('/saml2/aad/login') }}"
            class="h-20 mx-4 flex items-center justify-center transition-colors duration-300 border border-gray-800 rounded-md group hover:bg-gray-800 focus:outline-none"
        >
                  <span>
                    <img src="{{ asset('images/ms.png') }}" class="w-24" style="width: 90px;" />
                  </span>
            <span style="  margin-left: -25px;" class="text-sm font-medium text-gray-800 group-hover:text-black">Login with Microsoft Account</span>
        </a>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
