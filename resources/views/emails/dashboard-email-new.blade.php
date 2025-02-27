<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>EIRS</title>

    {{--    <style>--}}
    {{--        body {--}}
    {{--            margin: 0;--}}
    {{--            padding: 20px; /* Ensure padding is applied on all sides */--}}
    {{--            font-family: Arial, sans-serif;--}}
    {{--        }--}}

    {{--        .container {--}}
    {{--            padding: 20px; /* Adds padding around the content */--}}
    {{--        }--}}

    {{--        table {--}}
    {{--            width: 100%;--}}
    {{--            border-collapse: collapse;--}}
    {{--            margin-bottom: 20px;--}}
    {{--        }--}}

    {{--        th, td {--}}
    {{--            border: 1px solid #ddd;--}}
    {{--            padding: 10px; /* Adds padding inside table cells */--}}
    {{--            text-align: left;--}}
    {{--            word-wrap: break-word; /* Prevents overflow */--}}
    {{--        }--}}

    {{--        th {--}}
    {{--            background-color: #f2f2f2;--}}
    {{--        }--}}

    {{--        /* Ensure tables do not break across pages */--}}
    {{--        table, tr, td, th {--}}
    {{--            page-break-inside: avoid;--}}
    {{--        }--}}

    {{--        /* Fix right-side spacing issue */--}}
    {{--        td:last-child, th:last-child {--}}
    {{--            padding-right: 20px; /* Adds space to the right side of the last column */--}}
    {{--        }--}}

    {{--        /* Ensure consistent margins */--}}
    {{--        .container, table {--}}
    {{--            margin-left: 20px;--}}
    {{--            margin-right: 20px;--}}
    {{--        }--}}
    {{--    </style>--}}
    <style>
        table {
            width: 100%;
            font-size: 10px;
            table-layout: fixed; /* Ensures columns adjust properly */
            border-collapse: collapse;
        }

        th {
            width: 10%;
            background-color: #DDD9C4;
            color: black;
            text-align: center;
            word-wrap: break-word;
            border: 1px solid black;
        }

        td {
            width: auto;
            background-color: #ffffff;
            text-align: center;
            white-space: normal;
            word-wrap: break-word;
            border: 1px solid black;
        }

        @media print {
            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            th, td {
                border: 1px solid black; /* Ensures visibility in print */
            }
        }
    </style>
</head>

{{--<body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em">--}}

{{--<p style="font-weight: bold;">Dear {{ $userNames }}</p>--}}
{{--<?php--}}
{{--$country = \App\Models\Country::find($countryID);--}}
{{--$entities = \App\Models\Entity::where('country_id', $country->id)->get();--}}
{{--?>--}}
{{--<p>Please find below the latest progress tracking report, for {{ $country->name }}</p>--}}

{{--<h3 style="text-decoration: underline">Regular Dashboard Summary</h3>--}}

{{--@foreach($entities as $entity)--}}
{{--        <?php--}}
{{--        $regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId(2)->whereCountryId($country->id)->whereEntityId($entity->id)->get();--}}
{{--        $red = false;--}}
{{--        ?>--}}

{{--    @foreach($regular_yearly as $regular_year)--}}

{{--            <?php--}}
{{--            $red == true ? $red = true : $red = false;--}}
{{--            $event_name_yearly_regular = auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')--}}
{{--                ? \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)--}}
{{--                    ->whereCountryId($country->id)--}}
{{--                    ->whereEntityId($entity->id)--}}
{{--                    ->whereComplianceSubMenuId($regular_year->id)--}}
{{--                    ->where('occurrence', '=', 'Yearly')--}}
{{--                    ->where('event_type', '=', 'Regular')--}}
{{--                    ->whereAssignId(auth()->user()->id)--}}
{{--                    ->when($red, function ($query, $red) {--}}
{{--                        if ($red == true) {--}}
{{--                            return $query->whereStatus('Red');--}}
{{--                        }--}}
{{--                        return $query;--}}
{{--                    })--}}
{{--                    ->orderBy('event_name', 'asc')--}}
{{--                    ->orderBy('due_date', 'asc')--}}
{{--                    ->get()--}}
{{--                : \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)--}}
{{--                    ->whereCountryId($country->id)--}}
{{--                    ->whereEntityId($entity->id)--}}
{{--                    ->whereComplianceSubMenuId($regular_year->id)--}}
{{--                    ->where('occurrence', '=', 'Yearly')--}}
{{--                    ->where('event_type', '=', 'Regular')--}}
{{--                    ->when($red, function ($query, $red) {--}}
{{--                        if ($red == true) {--}}
{{--                            return $query->whereStatus('Red');--}}
{{--                        }--}}
{{--                        return $query;--}}
{{--                    })--}}
{{--                    ->orderBy('event_name', 'asc')--}}
{{--                    ->orderBy('due_date', 'asc')--}}
{{--                    ->get();--}}
{{--            ?>--}}

{{--        --}}{{-- Yearly Table --}}
{{--        @if(count($event_name_yearly_regular) > 0)--}}
{{--            <h5 style="text-decoration: underline">{{ $entity->entity_name ?? ''}}</h5>--}}
{{--            <div class="container">--}}
{{--                <table--}}
{{--                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"--}}
{{--                    style="zoom: 70%; margin-top: 20px;width: 100%">--}}
{{--                    <thead>--}}
{{--                    <tr>--}}
{{--                        <th style="background-color: #DDD9C4;color: black;width: 10%;"--}}
{{--                            class="text-center">Country--}}
{{--                        </th>--}}
{{--                        <td style="background-color: #DDD9C4"--}}
{{--                            class="text-center">{{ $country->name ?? '' }}</td>--}}
{{--                        <th style="background-color: #DDD9C4;color: black"--}}
{{--                            class="text-center">Entity--}}
{{--                        </th>--}}
{{--                        <td style="background-color: #DDD9C4"--}}
{{--                            class="text-center">{{ $entity->entity_name ?? ''}}</td>--}}
{{--                        <th style="background-color: #DDD9C4;color: black;width: 10%;"--}}
{{--                            class="text-center">Compliance Type--}}
{{--                        </th>--}}
{{--                        <td style="background-color: #DDD9C4;width: 10%;"--}}
{{--                            class="text-center">{{ $regular_year->sub_menu_name }}</td>--}}
{{--                        <th style="background-color: #DDD9C4;color: black;width: 10%;"--}}
{{--                            class="text-center">Frequency--}}
{{--                        </th>--}}
{{--                        <td style="background-color: #DDD9C4;width: 10%;"--}}
{{--                            class="text-center">--}}
{{--                            Annual--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    </thead>--}}
{{--                </table>--}}

{{--                <table--}}
{{--                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"--}}
{{--                    style="zoom: 70%; margin-top: 5px;width: 100%">--}}

{{--                    <tbody class="divide-y whitespace-nowrap">--}}

{{--                    <tr>--}}
{{--                        <th class="text-center"--}}
{{--                            style="background-color: #DDD9C4;color: black;width: 10%;">--}}
{{--                            Event Name--}}
{{--                        </th>--}}
{{--                        @foreach($event_name_yearly_regular as $event_name_year)--}}

{{--                            <td style="background-color: #ffffff;white-space: normal; word-wrap: break-word;width: 16px!important;"--}}
{{--                                class="text-center">{{ $event_name_year->event_name }}</td>--}}
{{--                        @endforeach--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <th class="text-center"--}}
{{--                            style="background-color: #DDD9C4;color: black;width: 10%;">--}}
{{--                            Due Date--}}
{{--                        </th>--}}
{{--                        @foreach($event_name_yearly_regular as $event_name_year)--}}
{{--                            @if($event_name_year->status == 'Red')--}}
{{--                                <td style="background-color: #F2DCDB"--}}
{{--                                    class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>--}}
{{--                            @elseif($event_name_year->status == 'Amber')--}}
{{--                                <td style="background-color: #FFFFCC"--}}
{{--                                    class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>--}}
{{--                            @elseif($event_name_year->status == 'Green')--}}
{{--                                <td style="background-color: #EBF1DE"--}}
{{--                                    class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>--}}
{{--                            @elseif($event_name_year->status == 'Blue')--}}
{{--                                <td style="background-color: #DCE6F1"--}}
{{--                                    class="text-center">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    </tr>--}}

{{--                    </tbody>--}}

{{--                </table>--}}
{{--            </div>--}}
{{--            <br>--}}
{{--        @endif--}}

{{--    @endforeach--}}
{{--    <br>--}}
{{--@endforeach--}}

{{--</body>--}}


<body itemscope itemtype="http://schema.org/EmailMessage"
      style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em">


<div style="margin-left: 10px;padding: 10px">
    <p style="font-weight: bold;">Dear {{ $userNames }}</p>
    <?php
    $country = \App\Models\Country::find($countryID);
    $entities = \App\Models\Entity::where('country_id', $country->id)->get();
    ?>

    <p>Please find below the latest progress tracking report, for {{ $country->name }} </p>



    @foreach($entities as $entity)
            <?php
            $regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->get();
            ?>
        <h3 style="text-decoration: underline">Regular Dashboard Summary</h3>
        @foreach($regular_yearly as $regular_year)

                <?php
//                                                            dd($is_red);
                $event_name_yearly_regular = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
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
                <?php
                $event_name_qtr_regular = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
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
                <?php
                $event_name_monthly_regular = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
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
            @if(count($event_name_yearly_regular) > 0 || count($event_name_qtr_regular) > 0 || count($event_name_monthly_regular) > 0)

                <h5 style="text-decoration: underline">{{ $entity->entity_name ?? ''}}</h5>

            @endif

            @if(count($event_name_yearly_regular) > 0)

                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 20px;width: 100%">
                    <thead style="line-height: normal">
                    <tr>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Country
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $country->name ?? '' }}
                        </td>
                        <th style="background-color: #DDD9C4;color: black"
                            class="text-center">Entity
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $entity->entity_name ?? ''}}
                        </td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Compliance Type
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">{{ $regular_year->sub_menu_name }}
                        </td>
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
                <br>
                <table style="zoom: 90%;margin-top: 10px">
                    <thead>
                    <tr>
                        <th>Event Name</th>
                        @foreach($event_name_yearly_regular as $event_name_year)
                            <td style="line-height: normal">{{ $event_name_year->event_name }}</td>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="divide-y whitespace-nowrap">

                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_yearly_regular as $event_name_year)
                            @if($event_name_year->status == 'Red')
                                <td style="background-color: #F2DCDB">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                            @elseif($event_name_year->status == 'Amber')
                                <td style="background-color: #FFFFCC">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                            @elseif($event_name_year->status == 'Green')
                                <td style="background-color: #EBF1DE">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                            @elseif($event_name_year->status == 'Blue')
                                <td style="background-color: #DCE6F1">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                            @endif
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            @endif

            {{--    2 Table--}}
            @if(count($event_name_qtr_regular) > 0)
                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 20px;width: 100%">
                    <thead style="line-height: normal">
                    <tr>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Country
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $country->name ?? '' }}
                        </td>
                        <th style="background-color: #DDD9C4;color: black"
                            class="text-center">Entity
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $entity->entity_name ?? ''}}
                        </td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Compliance Type
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">{{ $regular_year->sub_menu_name }}
                        </td>
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
                <br>
                <table style="zoom: 90%;margin-top: 10px">
                    <thead>
                    <tr>
                        <th>Event Name</th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            <td style="line-height: normal">{{ $event_name }}</td>
                        @endforeach

                    </tr>
                    </thead>
                    <tbody class="divide-y whitespace-nowrap">

                    @php
                        $statusColors = [
                            'Red' => '#F2DCDB',
                            'Amber' => '#FFFFCC',
                            'Green' => '#EBF1DE',
                            'Blue' => '#DCE6F1'
                        ];
                    @endphp
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[0]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[0]))
                                    {{ \Carbon\Carbon::parse($events[0]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[1]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[1]))
                                    {{ \Carbon\Carbon::parse($events[1]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[2]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[2]))
                                    {{ \Carbon\Carbon::parse($events[2]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_qtr_regular as $event_name => $events)
                            @php
                                $status = $events[3]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
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
            @endif

            {{--            <div style="page-break-before: always;">--}}
            {{--    3 Table--}}



            @if(count($event_name_monthly_regular) > 0)
                <table
                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                    style="zoom: 90%; margin-top: 20px;width: 100%">
                    <thead style="line-height: normal">
                    <tr>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Country
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $country->name ?? '' }}
                        </td>
                        <th style="background-color: #DDD9C4;color: black"
                            class="text-center">Entity
                        </th>
                        <td style="background-color: #DDD9C4"
                            class="text-center">{{ $entity->entity_name ?? ''}}
                        </td>
                        <th style="background-color: #DDD9C4;color: black;width: 10%;"
                            class="text-center">Compliance Type
                        </th>
                        <td style="background-color: #DDD9C4;width: 10%;"
                            class="text-center">{{ $regular_year->sub_menu_name }}
                        </td>
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
                <br>
                <table style="zoom: 90%;margin-top: 10px">
                    <thead>
                    <tr>
                        <th>Event Name</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            <td style="line-height: normal">{{ $event_name }}</td>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="divide-y whitespace-nowrap">

                    @php
                        $statusColors = [
                            'Red' => '#F2DCDB',
                            'Amber' => '#FFFFCC',
                            'Green' => '#EBF1DE',
                            'Blue' => '#DCE6F1'
                        ];
                    @endphp
{{--                    <tr>--}}
{{--                        <th>Due Date</th>--}}
{{--                        @foreach($event_name_monthly_regular as $event_name => $events)--}}
{{--                            @php--}}
{{--                                $status = $events[0]->status ?? 'default';--}}
{{--                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';--}}
{{--                            @endphp--}}
{{--                            <td style="background-color: {{ $backgroundColor }}">--}}
{{--                                @if(isset($events[0]))--}}
{{--                                    {{ \Carbon\Carbon::parse($events[0]->due_date)->format('d-M-Y') }}--}}
{{--                                @else--}}
{{--                                    ---}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        @endforeach--}}
{{--                    </tr>--}}
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[0]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[0]))
                                    {{ \Carbon\Carbon::parse($events[0]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[1]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[1]))
                                    {{ \Carbon\Carbon::parse($events[1]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[2]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[2]))
                                    {{ \Carbon\Carbon::parse($events[2]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[3]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[3]))
                                    {{ \Carbon\Carbon::parse($events[3]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[4]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[4]))
                                    {{ \Carbon\Carbon::parse($events[4]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[5]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[5]))
                                    {{ \Carbon\Carbon::parse($events[5]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[6]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[6]))
                                    {{ \Carbon\Carbon::parse($events[6]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[7]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[7]))
                                    {{ \Carbon\Carbon::parse($events[7]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[8]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[8]))
                                    {{ \Carbon\Carbon::parse($events[8]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[9]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[9]))
                                    {{ \Carbon\Carbon::parse($events[9]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[10]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
                                @if(isset($events[10]))
                                    {{ \Carbon\Carbon::parse($events[10]->due_date)->format('d-M-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        @foreach($event_name_monthly_regular as $event_name => $events)
                            @php
                                $status = $events[11]->status ?? 'default';
                                $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                            @endphp
                            <td style="background-color: {{ $backgroundColor }}">
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
            @endif
            {{--            </div>--}}
        @endforeach
        <br>
        {{--    @endforeach--}}

        <br>
        <div style="page-break-before: avoid">
            <h3 style="text-decoration: underline">Add-Hoc Dashboard Summary</h3>

            {{--        @foreach($entities as $entity)--}}
                <?php
                $regular_yearly = \App\Models\ComplianceSubMenu::whereCalendarYearId($calendar_year_id)->whereCountryId($country->id)->whereEntityId($entity->id)->get();
                ?>

            @foreach($regular_yearly as $regular_year)

                    <?php
//                                                            dd($is_red);
                    $event_name_yearly_regular = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                            ->whereCountryId($country->id)
                            ->whereEntityId($entity->id)
                            ->whereComplianceSubMenuId($regular_year->id)
                            ->where('occurrence', '=', 'Yearly')
                            ->where('event_type', '=', 'Add-Hoc')
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
                    <?php
                    $event_name_qtr_regular = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                            ->whereCountryId($country->id)
                            ->whereEntityId($entity->id)
                            ->whereComplianceSubMenuId($regular_year->id)
                            ->where('occurrence', '=', 'Qtr')
                            ->where('event_type', '=', 'Add-Hoc')
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
                    <?php
                    $event_name_monthly_regular = \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                            ->whereCountryId($country->id)
                            ->whereEntityId($entity->id)
                            ->whereComplianceSubMenuId($regular_year->id)
                            ->where('occurrence', '=', 'Monthly')
                            ->where('event_type', '=', 'Add-Hoc')
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
                @if(count($event_name_yearly_regular) > 0 || count($event_name_qtr_regular) > 0 || count($event_name_monthly_regular) > 0)

                    <h5 style="text-decoration: underline">{{ $entity->entity_name ?? ''}}</h5>

                @endif

                @if(count($event_name_yearly_regular) > 0)

                    <table
                        class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                        style="zoom: 90%; margin-top: 20px;width: 100%">
                        <thead style="line-height: normal">
                        <tr>
                            <th style="background-color: #DDD9C4;color: black;width: 10%;"
                                class="text-center">Country
                            </th>
                            <td style="background-color: #DDD9C4"
                                class="text-center">{{ $country->name ?? '' }}
                            </td>
                            <th style="background-color: #DDD9C4;color: black"
                                class="text-center">Entity
                            </th>
                            <td style="background-color: #DDD9C4"
                                class="text-center">{{ $entity->entity_name ?? ''}}
                            </td>
                            <th style="background-color: #DDD9C4;color: black;width: 10%;"
                                class="text-center">Compliance Type
                            </th>
                            <td style="background-color: #DDD9C4;width: 10%;"
                                class="text-center">{{ $regular_year->sub_menu_name }}
                            </td>
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
                    <br>
                    <table style="zoom: 90%;margin-top: 10px">
                        <thead>
                        <tr>
                            <th>Event Name</th>
                            @foreach($event_name_yearly_regular as $event_name_year)
                                <td style="line-height: normal">{{ $event_name_year->event_name }}</td>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody class="divide-y whitespace-nowrap">
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_yearly_regular as $event_name_year)
                                @if($event_name_year->status == 'Red')
                                    <td style="background-color: #F2DCDB">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                                @elseif($event_name_year->status == 'Amber')
                                    <td style="background-color: #FFFFCC">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                                @elseif($event_name_year->status == 'Green')
                                    <td style="background-color: #EBF1DE">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                                @elseif($event_name_year->status == 'Blue')
                                    <td style="background-color: #DCE6F1">{{ \Carbon\Carbon::parse($event_name_year->due_date)->format('d-M-Y') }}</td>
                                @endif
                            @endforeach
                        </tr>
                        </tbody>
                    </table>
                @endif

                {{--    2 Table--}}
                @if(count($event_name_qtr_regular) > 0)
                    <table
                        class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                        style="zoom: 90%; margin-top: 20px;width: 100%">
                        <thead style="line-height: normal">
                        <tr>
                            <th style="background-color: #DDD9C4;color: black;width: 10%;"
                                class="text-center">Country
                            </th>
                            <td style="background-color: #DDD9C4"
                                class="text-center">{{ $country->name ?? '' }}
                            </td>
                            <th style="background-color: #DDD9C4;color: black"
                                class="text-center">Entity
                            </th>
                            <td style="background-color: #DDD9C4"
                                class="text-center">{{ $entity->entity_name ?? ''}}
                            </td>
                            <th style="background-color: #DDD9C4;color: black;width: 10%;"
                                class="text-center">Compliance Type
                            </th>
                            <td style="background-color: #DDD9C4;width: 10%;"
                                class="text-center">{{ $regular_year->sub_menu_name }}
                            </td>
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
                    <br>
                    <table style="zoom: 90%;margin-top: 10px">
                        <thead>
                        <tr>
                            <th>Event Name</th>
                            @foreach($event_name_qtr_regular as $event_name => $events)
                                <td style="line-height: normal">{{ $event_name }}</td>
                            @endforeach

                        </tr>
                        </thead>
                        <tbody class="divide-y whitespace-nowrap">

                        @php
                            $statusColors = [
                                'Red' => '#F2DCDB',
                                'Amber' => '#FFFFCC',
                                'Green' => '#EBF1DE',
                                'Blue' => '#DCE6F1'
                            ];
                        @endphp
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_qtr_regular as $event_name => $events)
                                @php
                                    $status = $events[0]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[0]))
                                        {{ \Carbon\Carbon::parse($events[0]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_qtr_regular as $event_name => $events)
                                @php
                                    $status = $events[1]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[1]))
                                        {{ \Carbon\Carbon::parse($events[1]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_qtr_regular as $event_name => $events)
                                @php
                                    $status = $events[2]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[2]))
                                        {{ \Carbon\Carbon::parse($events[2]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_qtr_regular as $event_name => $events)
                                @php
                                    $status = $events[3]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
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
                @endif

                {{--    3 Table--}}
                @if(count($event_name_monthly_regular) > 0)
                    <table
                        class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                        style="zoom: 90%; margin-top: 20px;width: 100%">
                        <thead style="line-height: normal">
                        <tr>
                            <th style="background-color: #DDD9C4;color: black;width: 10%;"
                                class="text-center">Country
                            </th>
                            <td style="background-color: #DDD9C4"
                                class="text-center">{{ $country->name ?? '' }}
                            </td>
                            <th style="background-color: #DDD9C4;color: black"
                                class="text-center">Entity
                            </th>
                            <td style="background-color: #DDD9C4"
                                class="text-center">{{ $entity->entity_name ?? ''}}
                            </td>
                            <th style="background-color: #DDD9C4;color: black;width: 10%;"
                                class="text-center">Compliance Type
                            </th>
                            <td style="background-color: #DDD9C4;width: 10%;"
                                class="text-center">{{ $regular_year->sub_menu_name }}
                            </td>
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
                    <br>
                    <table style="zoom: 90%;margin-top: 10px">
                        <thead>
                        <tr>
                            <th>Event Name</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                <td style="line-height: normal">{{ $event_name }}</td>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody class="divide-y whitespace-nowrap">

                        @php
                            $statusColors = [
                                'Red' => '#F2DCDB',
                                'Amber' => '#FFFFCC',
                                'Green' => '#EBF1DE',
                                'Blue' => '#DCE6F1'
                            ];
                        @endphp
{{--                        <tr>--}}
{{--                            <th>Due Date</th>--}}
{{--                            @foreach($event_name_monthly_regular as $event_name => $events)--}}
{{--                                @php--}}
{{--                                    $status = $events[0]->status ?? 'default';--}}
{{--                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';--}}
{{--                                @endphp--}}
{{--                                <td style="background-color: {{ $backgroundColor }}">--}}
{{--                                    @if(isset($events[0]))--}}
{{--                                        {{ \Carbon\Carbon::parse($events[0]->due_date)->format('d-M-Y') }}--}}
{{--                                    @else--}}
{{--                                        ---}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                            @endforeach--}}
{{--                        </tr>--}}
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[0]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[0]))
                                        {{ \Carbon\Carbon::parse($events[0]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[1]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[1]))
                                        {{ \Carbon\Carbon::parse($events[1]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[2]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[2]))
                                        {{ \Carbon\Carbon::parse($events[2]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[3]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[3]))
                                        {{ \Carbon\Carbon::parse($events[3]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[4]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[4]))
                                        {{ \Carbon\Carbon::parse($events[4]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[5]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[5]))
                                        {{ \Carbon\Carbon::parse($events[5]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[6]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[6]))
                                        {{ \Carbon\Carbon::parse($events[6]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[7]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[7]))
                                        {{ \Carbon\Carbon::parse($events[7]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[8]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[8]))
                                        {{ \Carbon\Carbon::parse($events[8]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[9]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[9]))
                                        {{ \Carbon\Carbon::parse($events[9]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[10]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
                                    @if(isset($events[10]))
                                        {{ \Carbon\Carbon::parse($events[10]->due_date)->format('d-M-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            @foreach($event_name_monthly_regular as $event_name => $events)
                                @php
                                    $status = $events[11]->status ?? 'default';
                                    $backgroundColor = $statusColors[$status] ?? '#f6f4ed';
                                @endphp
                                <td style="background-color: {{ $backgroundColor }}">
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
                @endif

            @endforeach
            <br>
        </div>
    @endforeach
</div>
<br>

<br>

</body>

</html>
