<x-filament-panels::page>
{{--    <x-filament::breadcrumbs :breadcrumbs="[--}}
{{--    '/admin/compliance-list' => $this->compliance_sub_menu->country->name,--}}
{{--    '/admin/compliance-menu-list?country_id=' . $this->compliance_sub_menu->country->id => $this->compliance_sub_menu->complianceMenu->name,--}}
{{--    '/admin/compliance-sub-menu-list?compliance_menu_id=' . $this->compliance_sub_menu->complianceMenu->id => $this->compliance_sub_menu->name,--}}
{{--]" />--}}

    <nav aria-label="Breadcrumb" class="flex" >
        <ol class="flex space-x-1 rtl:space-x-reverse">
            <li>
                <a href="/admin/compliance-list" style="color: #000000;">{{$this->compliance_primary_sub_menu->document->name}}</a>
            </li>
            <li>
                &nbsp  > &nbsp
            </li>
            <li>
                <a href="/admin/compliance-menu-list?document_id={{$this->compliance_primary_sub_menu->document->id}}& calendar_year_id={{$this->calendar_year_id}}" style="color: #000000;">{{$this->compliance_primary_sub_menu->complianceMenu->entity->entity_name}}</a>
            </li>
            <li>
                &nbsp > &nbsp
            </li>
            <li>
                <a href="/admin/compliance-sub-menu-list?compliance_menu_id={{$this->compliance_primary_sub_menu->complianceMenu->id}}&calendar_year_id={{$this->calendar_year_id}}" style="color: #000000;">{{$this->compliance_primary_sub_menu->complianceSubMenu->sub_menu_name}}</a>
            </li>
            <li>
                &nbsp > &nbsp
            </li>
            <li>
                <a href="/admin/compliance-primary-sub-menu-list?compliant_menu_id={{$this->compliance_primary_sub_menu->complianceMenu->id}}&compliant_sub_menu_id={{$this->compliance_primary_sub_menu->complianceSubMenu->id}}&calendar_year_id={{$this->calendar_year_id}}" style="color: #000000;">{{$this->compliance_primary_sub_menu->event_name}}</a>
            </li>
            <li>
                &nbsp  - &nbsp
            </li>
            <li>
                <h1>  {{ $this->year->name }}</h1>
            </li>

        </ol>
    </nav>
    {{ $this->table }}
</x-filament-panels::page>
