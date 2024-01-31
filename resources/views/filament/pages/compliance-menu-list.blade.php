<x-filament-panels::page>
{{--    <x-filament::breadcrumbs :breadcrumbs="[--}}
{{--    '/admin/compliance-list' => $this->country->name,--}}
{{--]"/>--}}

    <nav aria-label="Breadcrumb" class="flex" >
        <ol class="flex space-x-1 rtl:space-x-reverse">
                <li>
                    <a href="/admin/compliance-list" style="color: #000000;">{{$this->country->name}}</a>
                </li>

        </ol>
    </nav>
    {{ $this->table }}
</x-filament-panels::page>
