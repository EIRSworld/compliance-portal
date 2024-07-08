
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
            class="text-center"> {{ $compliance_sub_menu->sub_menu_name }}</td>
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
<br>
<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 16px;">

    <tbody class="divide-y whitespace-nowrap">

    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Event Name
        </th>
        @foreach($event_name_yearly_regular as $event_name_year)
            <td style="background-color: #ffffff;font-weight: bold"
                class="text-center">{{ $event_name_year->event_name }}</td>
        @endforeach
    </tr>
    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Due Date
        </th>
        @foreach($event_name_yearly_regular as $event_name_year)
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
            class="text-center">{{ $compliance_sub_menu->sub_menu_name }}</td>
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
<table
    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
    style="zoom: 90%; margin-top: 20px;">


    <tbody class="divide-y whitespace-nowrap">
    <tr>
        <th class="text-center"
            style="background-color: #DDD9C4;color: black;">
            Event Name
        </th>
        @foreach($event_name_qtr_regular as $event_name => $events)
            <td class="text-center"
                style="font-weight: bold">{{ $event_name }}</td>
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

    <tr>
        <th class="text-center" rowspan="4"
            style="background-color: #DDD9C4; color: black; ">
            Due Date
        </th>
        @foreach($event_name_qtr_regular as $event_name => $events)
            @php
                $status = $events[0]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[0]))
                    {{ \Carbon\Carbon::parse($events[0]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_qtr_regular as $event_name => $events)
            @php
                $status = $events[1]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[1]))
                    {{ \Carbon\Carbon::parse($events[1]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_qtr_regular as $event_name => $events)
            @php
                $status = $events[2]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[2]))
                    {{ \Carbon\Carbon::parse($events[2]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_qtr_regular as $event_name => $events)
            @php
                $status = $events[3]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[3]))
                    {{ \Carbon\Carbon::parse($events[3]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
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
            class="text-center">{{ $compliance_sub_menu->sub_menu_name }}</td>
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
        @foreach($event_name_monthly_regular as $event_name => $events)
            <td class="text-center"
                style="font-weight: bold">{{ $event_name }}</td>
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

    <tr>
        <th class="text-center" rowspan="12"
            style="background-color: #DDD9C4; color: black; ">
            Due Date
        </th>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[0]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[0]))
                    {{ \Carbon\Carbon::parse($events[0]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[1]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[1]))
                    {{ \Carbon\Carbon::parse($events[1]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[2]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[2]))
                    {{ \Carbon\Carbon::parse($events[2]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[3]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[3]))
                    {{ \Carbon\Carbon::parse($events[3]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[4]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[4]))
                    {{ \Carbon\Carbon::parse($events[4]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[5]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[5]))
                    {{ \Carbon\Carbon::parse($events[5]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[6]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[6]))
                    {{ \Carbon\Carbon::parse($events[6]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[7]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[7]))
                    {{ \Carbon\Carbon::parse($events[7]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[8]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[8]))
                    {{ \Carbon\Carbon::parse($events[8]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[9]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[9]))
                    {{ \Carbon\Carbon::parse($events[9]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[10]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[10]))
                    {{ \Carbon\Carbon::parse($events[10]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>
    <tr>
        @foreach($event_name_monthly_regular as $event_name => $events)
            @php
                $status = $events[11]->status ?? 'default';
                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
            @endphp
            <td style="background-color: {{ $backgroundColor }}"
                class="text-center">
                @if(isset($events[11]))
                    {{ \Carbon\Carbon::parse($events[11]->due_date)->format('d-M-Y') }}
                @else
                    -
                @endif
            </td>
        @endforeach
    </tr>

    </tbody>

</table>
