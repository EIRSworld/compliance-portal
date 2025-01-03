<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>EIRS</title>
    <style>
        table {
            border: 1px solid #CCC;
            font-size: 13px;
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            padding: 5%;
            -moz-border-radius: 10px;
        }

        th,td {
            border: 1px solid #4f4c4c;
            border-collapse: collapse;
            padding: 0 5px;
        }

        td {
            /*height: 25px !important;*/
            white-space: nowrap !important;
        }

        .amount {
            text-align: right;
        }


    </style>
</head>


<body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em">

<p style="font-weight: bold;">Dear {{ implode(', ', $userNames) }}</p>
    <?php
        $country = \App\Models\Country::find($countryID);
        $entities = \App\Models\Entity::where('country_id', $country->id)->get();
        ?>
<p>Please find below the latest progress tracking report, for {{ $country->name }}</p>

<h3 style="text-decoration: underline">Regular Dashboard Summary</h3>

    @foreach($entities as $entity)
        <?php
            $regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->get();
            ?>

        @foreach($regular_yearly as $regular_year)

                <?php
//                                                            dd($is_red);
                $event_name_yearly_regular = auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')
                    ? \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Yearly')
                        ->where('event_type', '=', 'Regular')
                        ->whereAssignId(auth()->user()->id)
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get()
                    : \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Yearly')
                        ->where('event_type', '=', 'Regular')
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get();
                ?>



            {{--                                                    Yearly Table--}}
            @if(count($event_name_yearly_regular) > 0)

                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 20px;">
                    <thead>
                    <tr>
                        {{--                                                            <th></th>--}}
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Country
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $country->name ?? '' }}</td>
                        <th style="background-color: #DDD9C4;color: black"
                            class="text-center">Entity
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $entity->entity_name ?? ''}}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Compliance Type
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">{{ $regular_year->sub_menu_name }}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Frequency
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">
                            Annual
                        </td>
                    </tr>
                    </thead>
                </table>

                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 15px">

                    <tbody class="divide-y whitespace-nowrap">

                    <tr>
                        <th class="text-center"
                            style="background-color: #DDD9C4;color: black;width: 10%;">
                            Event Name
                        </th>
                        @foreach($event_name_yearly_regular as $event_name_year)

                            <td style="background-color: #ffffff;white-space: normal; word-wrap: break-word;width: 16px!important;"
                                class="text-center">{{ $event_name_year->event_name }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="text-center"
                            style="background-color: #DDD9C4;color: black;width: 10%;">
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
            @endif
                <?php
                $event_name_qtr_regular = auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')
                    ? \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Qtr')
                        ->where('event_type', '=', 'Regular')
                        ->whereAssignId(auth()->user()->id)
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get()->groupBy('event_name')
                    : \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Qtr')
                        ->where('event_type', '=', 'Regular')
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get()->groupBy('event_name');
                ?>

            {{--                                                    Qtr Table--}}
            @if(count($event_name_qtr_regular) > 0)
                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 20px;">
                    <thead>
                    <tr>
                        {{--                                                            <th></th>--}}
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Country
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $country->name ?? '' }}</td>
                        <th style="background-color: #DDD9C4;color: black"
                            class="text-center">Entity
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $entity->entity_name ?? ''}}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Compliance Type
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">{{ $regular_year->sub_menu_name }}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Frequency
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">
                            Quarterly
                        </td>
                    </tr>
                    </thead>
                </table>
                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 15px">


                    <tbody class="divide-y whitespace-nowrap">
                    <tr>
                        <th class="text-center"
                            style="background-color: #DDD9C4;color: black;width: 10%!important;">
                            Event Name
                        </th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            <td class="text-center"
                                style="background-color: #ffffff;white-space: normal; word-wrap: break-word;width: 16px!important;">{{ $event_name }}</td>
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[0]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[1]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[2]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[3]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
            @endif

                <?php
                $event_name_monthly_regular = auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')
                    ? \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Monthly')
                        ->where('event_type', '=', 'Regular')
                        ->whereAssignId(auth()->user()->id)
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get()->groupBy('event_name')
                    : \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Monthly')
                        ->where('event_type', '=', 'Regular')
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get()->groupBy('event_name');
                ?>


            {{--                                                    Monthly Table--}}
            @if(count($event_name_monthly_regular) > 0)

                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 20px;">
                    <thead>
                    <tr>
                        {{--                                                            <th></th>--}}
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Country
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $country->name ?? '' }}</td>
                        <th style="background-color: #DDD9C4;color: black"
                            class="text-center">Entity
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $entity->entity_name ?? ''}}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Compliance Type
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">{{ $regular_year->sub_menu_name }}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Frequency
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">
                            Monthly
                        </td>
                    </tr>
                    </thead>
                </table>
                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 15px">


                    <tbody class="divide-y whitespace-nowrap">
                    <tr>
                        <th class="text-center"
                            style="background-color: #DDD9C4;color: black;width: 10%!important;">
                            Event Name
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            <td class="text-center"
                                style="background-color: #ffffff;white-space: normal; word-wrap: break-word;width: 16px!important;">{{ $event_name }}</td>
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[0]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[1]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[2]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[3]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[4]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[5]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[6]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[7]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[8]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[9]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[10]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[11]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                <br>
            @endif

        @endforeach
        <br>
    @endforeach

<br>
<h3 style="text-decoration: underline">Ad-HOC Dashboard Summary</h3>

@foreach($entities as $entity)
        <?php
        $regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->get();
        ?>

        @foreach($regular_yearly as $regular_year)

                <?php
//                                                            dd($is_red);
                $event_name_yearly_regular = auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')
                    ? \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Yearly')
                        ->where('event_type', '=', 'Regular')
                        ->whereAssignId(auth()->user()->id)
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get()
                    : \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Yearly')
                        ->where('event_type', '=', 'Regular')
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get();
                ?>



            {{--                                                    Yearly Table--}}
            @if(count($event_name_yearly_regular) > 0)

                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 20px;">
                    <thead>
                    <tr>
                        {{--                                                            <th></th>--}}
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Country
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $country->name ?? '' }}</td>
                        <th style="background-color: #DDD9C4;color: black"
                            class="text-center">Entity
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $entity->entity_name ?? ''}}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Compliance Type
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">{{ $regular_year->sub_menu_name }}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Frequency
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
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
                            style="background-color: #DDD9C4;color: black;width: 10%;">
                            Event Name
                        </th>
                        @foreach($event_name_yearly_regular as $event_name_year)

                            <td style="background-color: #ffffff;white-space: normal; word-wrap: break-word;width: 16px!important;"
                                class="text-center">{{ $event_name_year->event_name }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="text-center"
                            style="background-color: #DDD9C4;color: black;width: 10%;">
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
            @endif
                <?php
                $event_name_qtr_regular = auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')
                    ? \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Qtr')
                        ->where('event_type', '=', 'Regular')
                        ->whereAssignId(auth()->user()->id)
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get()->groupBy('event_name')
                    : \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Qtr')
                        ->where('event_type', '=', 'Regular')
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get()->groupBy('event_name');
                ?>

            {{--                                                    Qtr Table--}}
            @if(count($event_name_qtr_regular) > 0)
                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 20px;">
                    <thead>
                    <tr>
                        {{--                                                            <th></th>--}}
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Country
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $country->name ?? '' }}</td>
                        <th style="background-color: #DDD9C4;color: black"
                            class="text-center">Entity
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $entity->entity_name ?? ''}}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Compliance Type
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">{{ $regular_year->sub_menu_name }}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Frequency
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
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
                            style="background-color: #DDD9C4;color: black;width: 10%!important;">
                            Event Name
                        </th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            <td class="text-center"
                                style="background-color: #ffffff;white-space: normal; word-wrap: break-word;width: 16px!important;">{{ $event_name }}</td>
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[0]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[1]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[2]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[3]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
            @endif

                <?php
                $event_name_monthly_regular = auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')
                    ? \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Monthly')
                        ->where('event_type', '=', 'Regular')
                        ->whereAssignId(auth()->user()->id)
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get()->groupBy('event_name')
                    : \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                        ->whereCountryId($country->id)
                        ->whereEntityId($entity->id)
                        ->whereComplianceSubMenuId($regular_year->id)
                        ->where('occurrence', '=', 'Monthly')
                        ->where('event_type', '=', 'Regular')
                        ->when($red, function ($query, $red) {
                            if ($red == true) {
                                return $query->whereStatus('Red');
                            }
                            return $query;
                        })
                        ->orderBy('event_name', 'asc')
                        ->orderBy('due_date', 'asc')
                        ->get()->groupBy('event_name');
                ?>


            {{--                                                    Monthly Table--}}
            @if(count($event_name_monthly_regular) > 0)

                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 20px;">
                    <thead>
                    <tr>
                        {{--                                                            <th></th>--}}
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Country
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $country->name ?? '' }}</td>
                        <th style="background-color: #DDD9C4;color: black"
                            class="text-center">Entity
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $entity->entity_name ?? ''}}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Compliance Type
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">{{ $regular_year->sub_menu_name }}</td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Frequency
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
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
                            style="background-color: #DDD9C4;color: black;width: 10%!important;">
                            Event Name
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            <td class="text-center"
                                style="background-color: #ffffff;white-space: normal; word-wrap: break-word;width: 16px!important;">{{ $event_name }}</td>
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[0]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[1]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[2]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[3]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[4]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[5]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[6]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[7]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[8]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[9]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[10]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                        <th class="text-center"
                            style="background-color: #DDD9C4; color: black; width: 10%!important;">
                            Due Date
                        </th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[11]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}; height: 20px"
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
                <br>
            @endif

            <br>
        @endforeach
    <br>
@endforeach


</body>

</html>
