<x-filament-panels::page>

    <nav aria-label="Breadcrumb" class="flex" >
        <ol class="flex space-x-1 rtl:space-x-reverse">
            <li>
                <a href="/admin/compliance-list" style="color: #000000;">{{$this->compliance_sub_menu->document->name}}</a>
            </li>
            <li>
                &nbsp  > &nbsp
            </li>
            <li>
                <a href="/admin/compliance-menu-list?document_id={{$this->compliance_sub_menu->document->id}}& calendar_year_id={{$this->calendar_year_id}}" style="color: #000000;">{{$this->compliance_menu->name}}</a>
            </li>
            <li>
                &nbsp  > &nbsp
            </li>
            <li>
                <a href="/admin/compliance-sub-menu-list?compliance_menu_id={{$this->compliance_sub_menu->compliance_menu_id}}& calendar_year_id={{$this->calendar_year_id}}" style="color: #000000;">{{$this->compliance_sub_menu->sub_menu_name}}</a>
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
