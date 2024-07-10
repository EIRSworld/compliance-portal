{{--Operations--}}
<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">
    <thead>
    <tr>
        {{--                                                            <th></th>--}}
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Country
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $country->name ?? '' }}</td>
        <th style="background-color: #DDD9C4;color: black"
            class="text-center">Entity
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $entity->entity_name ?? ''}}</td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Compliance Type
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center"> Operations
        </td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Frequency
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">
            Annual
        </td>
    </tr>
    </thead>
</table>
<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 16px;">

    <tbody class="divide-y whitespace-nowrap">

    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Event Name
        </th>
        @foreach($event_name_yearly_addhoc as $event_name_year)
            {{--        @dd($event_name_year)--}}
                <?php
                $compliance_sub_menu = \App\Models\ComplianceSubMenu::whereId($event_name_year->compliance_sub_menu_id)->first()
                ?>
            @if($compliance_sub_menu->sub_menu_name === 'Operations')
                <td style="background-color: #ffffff;font-weight: bold"
                    class="text-center">{{ $event_name_year->event_name }}</td>
            @endif
        @endforeach
    </tr>
    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Due Date
        </th>
        @foreach($event_name_yearly_addhoc as $event_name_year)
            @if($compliance_sub_menu->sub_menu_name === 'Operations')
                @if($event_name_year->status == 'Red')
                    <td style="background-color: #F2DCDB"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @elseif($event_name_year->status == 'Amber')
                    <td style="background-color: #FFFFCC"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @elseif($event_name_year->status == 'Green')
                    <td style="background-color: #EBF1DE"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @elseif($event_name_year->status == 'Blue')
                    <td style="background-color: #DCE6F1"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @endif
            @endif
        @endforeach
    </tr>


    </tbody>

</table>

<br>

<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">
    <thead>
    <tr>
        {{--                                                            <th></th>--}}
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Country
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $country->name ?? '' }}</td>
        <th style="background-color: #DDD9C4;color: black"
            class="text-center">Entity
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $entity->entity_name ?? ''}}</td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Compliance Type
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">Operations
        </td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Frequency
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">
            Quarterly
        </td>
    </tr>
    </thead>
</table>
<table class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table" style="zoom: 90%; margin-top: 20px;">
    <tbody class="divide-y whitespace-nowrap">
    <tr>
        <th class="text-center" style="background-color: #DDD9C4;color: black;">Event Name</th>
        @foreach($event_name_qtr_addhoc as $index => $event_name_qtr)
            @if(optional($event_name_qtr[0]->complianceSubMenu)->sub_menu_name === 'Operations')
                <td class="text-center" style="font-weight: bold">{{ $event_name_qtr[0]->event_name }}</td>
            @endif
        @endforeach
    </tr>

    @php
        $statusColors = [
            'Red' => '#F2DCDB',
            'Amber' => '#FFFFCC',
            'Green' => '#EBF1DE',
            'Blue' => '#DCE6F1'
        ];
    @endphp

    @for($i = 0; $i < 4; $i++)
        <tr>
            @if($i === 0)
                <th class="text-center" rowspan="4" style="background-color: #DDD9C4; color: black;">Due Date</th>
            @endif
            @foreach($event_name_qtr_addhoc as $event_name => $events)
                @if(isset($events[$i]) && optional($events[$i]->complianceSubMenu)->sub_menu_name === 'Operations')
                    @php
                        $status = $events[$i]->status ?? 'default';
                        $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                    @endphp
                    <td style="background-color: {{ $backgroundColor }}" class="text-center">
                        {{ \Carbon\Carbon::parse($events[$i]->due_date)->format('d-M-Y') ?? '-' }}
                    </td>
                @endif
            @endforeach
        </tr>
    @endfor
    </tbody>
</table>

<br>

<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">
    <thead>
    <tr>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Country
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $country->name ?? '' }}</td>
        <th style="background-color: #DDD9C4;color: black"
            class="text-center">Entity
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $entity->entity_name ?? ''}}</td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Compliance Type
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">Operation
        </td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Frequency
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">
            Monthly
        </td>
    </tr>
    </thead>
</table>
<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">


    <tbody class="divide-y whitespace-nowrap">
    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Event Name
        </th>
        @foreach($event_name_monthly_addhoc as $event_name_monthly)
                @if(optional($event_name_monthly[0]->complianceSubMenu)->sub_menu_name === 'Operations')
                <td class="text-center"
                    style="font-weight: bold">{{ $event_name_monthly[0]->event_name }}</td>
            @endif
        @endforeach
    </tr>


    @php
        $statusColors = [
            'Red' => '#F2DCDB',
            'Amber' => '#FFFFCC',
            'Green' => '#EBF1DE',
            'Blue' => '#DCE6F1'
        ];
    @endphp
    @for($i = 0; $i < 12; $i++)
        <tr>
            @if($i === 0)
                <th class="text-center" rowspan="12" style="background-color: #DDD9C4; color: black;">Due Date</th>
            @endif
            @foreach($event_name_monthly_addhoc as $event_name => $events)
                @if(isset($events[$i]) && optional($events[$i]->complianceSubMenu)->sub_menu_name === 'Operations')
                    @php
                        $status = $events[$i]->status ?? 'default';
                        $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                    @endphp
                    <td style="background-color: {{ $backgroundColor }}" class="text-center">
                        {{ \Carbon\Carbon::parse($events[$i]->due_date)->format('d-M-Y') ?? '-' }}
                    </td>
                @endif
            @endforeach
        </tr>
    @endfor
    </tbody>

</table>




{{--Finance--}}

<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">
    <thead>
    <tr>

        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Country
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $country->name ?? '' }}</td>
        <th style="background-color: #DDD9C4;color: black"
            class="text-center">Entity
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $entity->entity_name ?? ''}}</td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Compliance Type
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center"> Finance
        </td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Frequency
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">
            Annual
        </td>
    </tr>
    </thead>
</table>
<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 16px;">

    <tbody class="divide-y whitespace-nowrap">

    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Event Name
        </th>
        @foreach($event_name_yearly_addhoc as $event_name_year)
{{--                    @dd($event_name_year)--}}
                <?php
                $compliance_sub_menu = \App\Models\ComplianceSubMenu::whereId($event_name_year->compliance_sub_menu_id)->first()
                ?>
            @if($compliance_sub_menu->sub_menu_name === 'Finance')
                <td style="background-color: #ffffff;font-weight: bold"
                    class="text-center">{{ $event_name_year->event_name }}</td>
            @endif
        @endforeach
    </tr>
    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Due Date
        </th>
        @foreach($event_name_yearly_addhoc as $event_name_year)
            @if($compliance_sub_menu->sub_menu_name === 'Finance')
                @if($event_name_year->status == 'Red')
                    <td style="background-color: #F2DCDB"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @elseif($event_name_year->status == 'Amber')
                    <td style="background-color: #FFFFCC"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @elseif($event_name_year->status == 'Green')
                    <td style="background-color: #EBF1DE"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @elseif($event_name_year->status == 'Blue')
                    <td style="background-color: #DCE6F1"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @endif
            @endif
        @endforeach
    </tr>


    </tbody>

</table>

<br>

<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">
    <thead>
    <tr>
        {{--                                                            <th></th>--}}
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Country
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $country->name ?? '' }}</td>
        <th style="background-color: #DDD9C4;color: black"
            class="text-center">Entity
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $entity->entity_name ?? ''}}</td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Compliance Type
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">Finance
        </td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Frequency
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">
            Quarterly
        </td>
    </tr>
    </thead>
</table>
<table class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table" style="zoom: 90%; margin-top: 20px;">
    <tbody class="divide-y whitespace-nowrap">
    <tr>
        <th class="text-center" style="background-color: #DDD9C4;color: black;">Event Name</th>
        @foreach($event_name_qtr_addhoc as $index => $event_name_qtr)
            @if(optional($event_name_qtr[0]->complianceSubMenu)->sub_menu_name === 'Finance')
                <td class="text-center" style="font-weight: bold">{{ $event_name_qtr[0]->event_name }}</td>
            @endif
        @endforeach
    </tr>

    @php
        $statusColors = [
            'Red' => '#F2DCDB',
            'Amber' => '#FFFFCC',
            'Green' => '#EBF1DE',
            'Blue' => '#DCE6F1'
        ];
    @endphp

    @for($i = 0; $i < 4; $i++)
        <tr>
            @if($i === 0)
                <th class="text-center" rowspan="4" style="background-color: #DDD9C4; color: black;">Due Date</th>
            @endif
            @foreach($event_name_qtr_addhoc as $event_name => $events)
                @if(isset($events[$i]) && optional($events[$i]->complianceSubMenu)->sub_menu_name === 'Finance')
                    @php
                        $status = $events[$i]->status ?? 'default';
                        $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                    @endphp
                    <td style="background-color: {{ $backgroundColor }}" class="text-center">
                        {{ \Carbon\Carbon::parse($events[$i]->due_date)->format('d-M-Y') ?? '-' }}
                    </td>
                @endif
            @endforeach
        </tr>
    @endfor
    </tbody>
</table>

<br>

<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">
    <thead>
    <tr>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Country
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $country->name ?? '' }}</td>
        <th style="background-color: #DDD9C4;color: black"
            class="text-center">Entity
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $entity->entity_name ?? ''}}</td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Compliance Type
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">Finance
        </td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Frequency
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">
            Monthly
        </td>
    </tr>
    </thead>
</table>
<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">


    <tbody class="divide-y whitespace-nowrap">
    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Event Name
        </th>
        @foreach($event_name_monthly_addhoc as $event_name_monthly)
            @if(optional($event_name_monthly[0]->complianceSubMenu)->sub_menu_name === 'Finance')
                <td class="text-center"
                    style="font-weight: bold">{{ $event_name_monthly[0]->event_name }}</td>
            @endif
        @endforeach
    </tr>


    @php
        $statusColors = [
            'Red' => '#F2DCDB',
            'Amber' => '#FFFFCC',
            'Green' => '#EBF1DE',
            'Blue' => '#DCE6F1'
        ];
    @endphp
    @for($i = 0; $i < 12; $i++)
        <tr>
            @if($i === 0)
                <th class="text-center" rowspan="12" style="background-color: #DDD9C4; color: black;">Due Date</th>
            @endif
            @foreach($event_name_monthly_addhoc as $event_name => $events)
                @if(isset($events[$i]) && optional($events[$i]->complianceSubMenu)->sub_menu_name === 'Finance')
                    @php
                        $status = $events[$i]->status ?? 'default';
                        $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                    @endphp
                    <td style="background-color: {{ $backgroundColor }}" class="text-center">
                        {{ \Carbon\Carbon::parse($events[$i]->due_date)->format('d-M-Y') ?? '-' }}
                    </td>
                @endif
            @endforeach
        </tr>
    @endfor
    </tbody>

</table>




{{--HR--}}
<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">
    <thead>
    <tr>

        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Country
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $country->name ?? '' }}</td>
        <th style="background-color: #DDD9C4;color: black"
            class="text-center">Entity
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $entity->entity_name ?? ''}}</td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Compliance Type
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center"> HR
        </td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Frequency
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">
            Annual
        </td>
    </tr>
    </thead>
</table>
<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 16px;">

    <tbody class="divide-y whitespace-nowrap">

    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Event Name
        </th>
        @foreach($event_name_yearly_addhoc as $event_name_year)
{{--            @dd($event_name_year)--}}
                <?php
                $compliance_sub_menu = \App\Models\ComplianceSubMenu::whereId($event_name_year->compliance_sub_menu_id)->first()
                ?>
            @if($compliance_sub_menu->sub_menu_name === 'HR')
                <td style="background-color: #ffffff;font-weight: bold"
                    class="text-center">{{ $event_name_year->event_name }}</td>
            @endif
        @endforeach
    </tr>
    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Due Date
        </th>
        @foreach($event_name_yearly_addhoc as $event_name_year)
            @if($compliance_sub_menu->sub_menu_name === 'HR')
                @if($event_name_year->status == 'Red')
                    <td style="background-color: #F2DCDB"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @elseif($event_name_year->status == 'Amber')
                    <td style="background-color: #FFFFCC"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @elseif($event_name_year->status == 'Green')
                    <td style="background-color: #EBF1DE"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @elseif($event_name_year->status == 'Blue')
                    <td style="background-color: #DCE6F1"
                        class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                @endif
            @endif
        @endforeach
    </tr>


    </tbody>

</table>

<br>

<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">
    <thead>
    <tr>
        {{--                                                            <th></th>--}}
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Country
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $country->name ?? '' }}</td>
        <th style="background-color: #DDD9C4;color: black"
            class="text-center">Entity
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $entity->entity_name ?? ''}}</td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Compliance Type
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">HR
        </td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Frequency
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">
            Quarterly
        </td>
    </tr>
    </thead>
</table>
<table class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table" style="zoom: 90%; margin-top: 20px;">
    <tbody class="divide-y whitespace-nowrap">
    <tr>
        <th class="text-center" style="background-color: #DDD9C4;color: black;">Event Name</th>
        @foreach($event_name_qtr_addhoc as $index => $event_name_qtr)
            @if(optional($event_name_qtr[0]->complianceSubMenu)->sub_menu_name === 'HR')
                <td class="text-center" style="font-weight: bold">{{ $event_name_qtr[0]->event_name }}</td>
            @endif
        @endforeach
    </tr>

    @php
        $statusColors = [
            'Red' => '#F2DCDB',
            'Amber' => '#FFFFCC',
            'Green' => '#EBF1DE',
            'Blue' => '#DCE6F1'
        ];
    @endphp

    @for($i = 0; $i < 4; $i++)
        <tr>
            @if($i === 0)
                <th class="text-center" rowspan="4" style="background-color: #DDD9C4; color: black;">Due Date</th>
            @endif
            @foreach($event_name_qtr_addhoc as $event_name => $events)
                @if(isset($events[$i]) && optional($events[$i]->complianceSubMenu)->sub_menu_name === 'HR')
                    @php
                        $status = $events[$i]->status ?? 'default';
                        $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                    @endphp
                    <td style="background-color: {{ $backgroundColor }}" class="text-center">
                        {{ \Carbon\Carbon::parse($events[$i]->due_date)->format('d-M-Y') ?? '-' }}
                    </td>
                @endif
            @endforeach
        </tr>
    @endfor
    </tbody>
</table>

<br>

<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">
    <thead>
    <tr>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Country
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $country->name ?? '' }}</td>
        <th style="background-color: #DDD9C4;color: black"
            class="text-center">Entity
        </th>
        <td style="background-color: #DDD9C4"
            class="text-center">{{ $entity->entity_name ?? ''}}</td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Compliance Type
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">HR
        </td>
        <th style="background-color: #DDD9C4;color: black;"
            class="text-center">Frequency
        </th>
        <td style="background-color: #DDD9C4;"
            class="text-center">
            Monthly
        </td>
    </tr>
    </thead>
</table>
<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">


    <tbody class="divide-y whitespace-nowrap">
    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Event Name
        </th>
        @foreach($event_name_monthly_addhoc as $event_name_monthly)
            @if(optional($event_name_monthly[0]->complianceSubMenu)->sub_menu_name === 'HR')
                <td class="text-center"
                    style="font-weight: bold">{{ $event_name_monthly[0]->event_name }}</td>
            @endif
        @endforeach
    </tr>


    @php
        $statusColors = [
            'Red' => '#F2DCDB',
            'Amber' => '#FFFFCC',
            'Green' => '#EBF1DE',
            'Blue' => '#DCE6F1'
        ];
    @endphp
    @for($i = 0; $i < 12; $i++)
        <tr>
            @if($i === 0)
                <th class="text-center" rowspan="12" style="background-color: #DDD9C4; color: black;">Due Date</th>
            @endif
            @foreach($event_name_monthly_addhoc as $event_name => $events)
                @if(isset($events[$i]) && optional($events[$i]->complianceSubMenu)->sub_menu_name === 'HR')
                    @php
                        $status = $events[$i]->status ?? 'default';
                        $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                    @endphp
                    <td style="background-color: {{ $backgroundColor }}" class="text-center">
                        {{ \Carbon\Carbon::parse($events[$i]->due_date)->format('d-M-Y') ?? '-' }}
                    </td>
                @endif
            @endforeach
        </tr>
    @endfor
    </tbody>

</table>
