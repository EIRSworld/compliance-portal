<x-filament-widgets::widget>

    <style>
        .filament-tables-header-cell button {
            justify-content: right;
        }

        .filament-tables-row td {
            text-align: right;
        }

        .custom-heading th {
            /*text-align: center;*/
        }

        th {
            text-align: right;
        }

        td, th {
            padding: 5px !important;
            border: 1px solid #ccc !important;
        }

        .border-new {
            border: 1px solid #fff !important;
        }

        .custom-heading th {
            /*text-align: left;*/
        }

        .filament-tables-row td {
            text-align: left;
        }

        .amount {
            text-align: right;
        }

        .w-28 {
            width: 7rem;
        }

        .legend {
            display: flex;
            /*margin-left: 80%;*/
            flex-direction: row;
            align-items: center;
            margin-bottom: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            margin-right: 5px;
            border-radius: 3px;
        }

        .red {
            background-color: #F2DCDB;
        }

        .amber {
            background-color: #FFFFCC;
        }

        .green {
            background-color: #EBF1DE;
        }

        .blue {
            background-color: #DCE6F1;
        }
    </style>

    <div class="legend">
        <div class="legend-item">
            <div class="legend-color red"></div>
            <span>Very Critical</span>
        </div>
        <div class="legend-item">
            <div class="legend-color amber"></div>
            <span>Event needs attention</span>
        </div>
        <div class="legend-item">
            <div class="legend-color green"></div>
            <span>Uploaded</span>
        </div>
        <div class="legend-item">
            <div class="legend-color blue"></div>
            <span>Approved</span>
        </div>
        <div>
            <a href="{{ route('report.new-dashboard-export', ['id' => $calendar_year_id, 'country_id' => $country->id ?? 0,  'entity_id' => $entity->id ?? 0,
 'red' => $red ? 'true' : 'false' ]) }}"
               style="padding: 5px;" target="_blank">
                <x-filament::button>Export</x-filament::button>
            </a>
        </div>
    </div>
    {{ $this->form }}


    <div class="grid grid-cols-1      filament-forms-component-container gap-6" style="margin-top: 35px">
        <div class=" col-span-1     ">
            <div x-data="{

        tab: null,

        init: function () {
            this.tab = this.getTabs()[1 - 1]
        },

        getTabs: function () {
            return JSON.parse(this.$refs.tabsData.value)
        },

    }" class="filament-forms-tabs-component rounded-xl shadow-sm border border-gray-300 bg-white">
                <input type="hidden"
                       value="[&quot;-label-1-tab&quot;,&quot;-label-2-tab&quot;,&quot;-label-3-tab&quot;]"
                       x-ref="tabsData">

                <div aria-label="Heading" role="tablist"
                     class="filament-forms-tabs-component-header rounded-t-xl flex overflow-y-auto bg-gray-100"
                     style="width: 100%;">


                    <button style="width: 50%;font-size: 16px;" type="button" aria-controls="-label-1-tab"
                            x-bind:aria-selected="tab === '-label-1-tab'"
                            x-on:click="tab = '-label-1-tab'" role="tab"
                            x-bind:tabindex="tab === '-label-1-tab' ? 0 : -1"
                            class="filament-forms-tabs-component-button flex items-center gap-2 shrink-0 p-3 text-sm font-medium filament-forms-tabs-component-button-active bg-white text-primary-600"
                            x-bind:class="{
                    'text-gray-500 ': tab !== '-label-1-tab',
                    'filament-forms-tabs-component-button-active bg-white text-primary-600 ': tab === '-label-1-tab',
                }" aria-selected="true" tabindex="0">

                        <span>Regular Dashboard Summary </span>

                    </button>
                    <button style="width: 50%;font-size: 16px;" type="button" aria-controls="-label-2-tab"
                            x-bind:aria-selected="tab === '-label-2-tab'"
                            x-on:click="tab = '-label-2-tab'" role="tab"
                            x-bind:tabindex="tab === '-label-2-tab' ? 0 : -1"
                            class="filament-forms-tabs-component-button flex items-center gap-2 shrink-0 p-3 text-sm font-medium filament-forms-tabs-component-button-active bg-white text-primary-600"
                            x-bind:class="{
                    'text-gray-500 ': tab !== '-label-2-tab',
                    'filament-forms-tabs-component-button-active bg-white text-primary-600 ': tab === '-label-2-tab',
                }" aria-selected="true" tabindex="0">

                        <span>Add-Hoc Dashboard Summary</span>

                    </button>
                </div>

                <div aria-labelledby="-label-1-tab" id="-label-1-tab" role="tabpanel" tabindex="0"
                     x-bind:class="{ 'invisible h-0 p-0 overflow-y-hidden': tab !== '-label-1-tab', 'p-6': tab === '-label-1-tab' }"
                     x-on:expand-concealing-component.window="
        error = $el.querySelector('[data-validation-error]')

        if (! error) {
            return
        }

        tab = '-label-1-tab'
        if (document.body.querySelector('[data-validation-error]') !== error) {
            return
        }

        setTimeout(() => $el.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'start' }), 200)
    " class="filament-forms-tabs-component-tab focus:outline-none p-6">
                    {{--                    table 1--}}
                    <div class="grid grid-cols-1      filament-forms-component-container gap-6">
                        <div wire:key="hZhWt43N7BND5nGsiZ1S.one.Filament\Forms\Components\TextInput"
                             class=" col-span-1     ">
                            <div class="filament-forms-field-wrapper">

                                <div class="space-y-2">

                                    <div class="filament-tables-component" style="margin: 0; ">
                                        <div
                                            class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container"
                                            style="width: 100%">

                                            <div class="overflow-y-auto relative rounded-t-xl">


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
                                                                ->get();
                                                        ?>



                                                    {{--                                                    Yearly Table--}}
                                                    @if($event_name_yearly_regular)

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

                                                    @endif
                                                    <br>
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
                                                                ->get()->groupBy('event_name');
                                                        ?>

                                                    {{--                                                    Qtr Table--}}
                                                    @if($event_name_qtr_regular)
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
                                                                <th class="text-center" rowspan="4"
                                                                    style="background-color: #DDD9C4; color: black; width: 10%!important;">
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

                                                    @endif

                                                    <br>
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
                                                                ->get()->groupBy('event_name');
                                                        ?>


                                                    {{--                                                    Monthly Table--}}
                                                    @if($event_name_monthly_regular)

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
                                                                <th class="text-center" rowspan="12"
                                                                    style="background-color: #DDD9C4; color: black; width: 10%!important;">
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
                                                    @endif

                                                    <br>
                                                    <hr style="color: #191919;background-color: #191919">
                                                    <hr style="color: #191919;background-color: #191919">
                                                    <hr style="color: #191919;background-color: #191919">
                                                    <hr style="color: #191919;background-color: #191919">
                                                    <hr style="color: #191919;background-color: #191919">
                                                @endforeach
                                            </div>


                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>


                </div>


                <div aria-labelledby="-label-2-tab" id="-label-2-tab" role="tabpanel" tabindex="0"
                     x-bind:class="{ 'invisible h-0 p-0 overflow-y-hidden': tab !== '-label-2-tab', 'p-6': tab === '-label-2-tab' }"
                     x-on:expand-concealing-component.window="
        error = $el.querySelector('[data-validation-error]')

        if (! error) {
            return
        }

        tab = '-label-2-tab'
        if (document.body.querySelector('[data-validation-error]') !== error) {
            return
        }

        setTimeout(() => $el.scrollIntoView({ behavior: 'smooth', block: 'start', inline: 'start' }), 200)
    " class="filament-forms-tabs-component-tab focus:outline-none p-6">


                    <div class="grid grid-cols-1      filament-forms-component-container gap-6">
                        <div wire:key="hZhWt43N7BND5nGsiZ1S.one.Filament\Forms\Components\TextInput"
                             class=" col-span-1     ">
                            <div class="filament-forms-field-wrapper">

                                <div class="space-y-2">

                                    <div class="filament-tables-component" style="margin: 0; ">
                                        <div
                                            class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container"
                                            style="width: 100%">

                                            <div class="overflow-y-auto relative rounded-t-xl">

                                                @foreach($regular_yearly as $regular_year)
                                                        <?php
                                                        $event_name_yearly_regular = auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')
                                                            ? \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                                                                ->whereCountryId($country->id)
                                                                ->whereEntityId($entity->id)
                                                                ->whereComplianceSubMenuId($regular_year->id)
                                                                ->where('occurrence', '=', 'Yearly')
                                                                ->where('event_type', '=', 'Add-Hoc')
                                                                ->whereAssignId(auth()->user()->id)
                                                                ->when($red, function ($query, $red) {
                                                                    if ($red == true) {
                                                                        return $query->whereStatus('Red');
                                                                    }
                                                                    return $query;
                                                                })
                                                                ->orderBy('event_name', 'asc')
                                                                ->get()
                                                            : \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
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
                                                                ->get();
                                                        ?>



                                                    {{--                                                    Yearly Table--}}
                                                    @if($event_name_yearly_regular)

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

                                                                    <td style="background-color: #ffffff;white-space: normal; word-wrap: break-word;"
                                                                        class="text-center">
                                                                        {{ $event_name_year->event_name }}
                                                                    </td>
                                                                    {{--                                                                    <td style="background-color: #ffffff;font-weight: bold"--}}
                                                                    {{--                                                                        class="text-center">{{ $event_name_year->event_name }}</td>--}}
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
                                                    @endif
                                                    <br>
                                                        <?php
                                                        $event_name_qtr_regular = auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')
                                                            ? \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                                                                ->whereCountryId($country->id)
                                                                ->whereEntityId($entity->id)
                                                                ->whereComplianceSubMenuId($regular_year->id)
                                                                ->where('occurrence', '=', 'Qtr')
                                                                ->where('event_type', '=', 'Add-Hoc')
                                                                ->whereAssignId(auth()->user()->id)
                                                                ->when($red, function ($query, $red) {
                                                                    if ($red == true) {
                                                                        return $query->whereStatus('Red');
                                                                    }
                                                                    return $query;
                                                                })
                                                                ->orderBy('event_name', 'asc')
                                                                ->get()->groupBy('event_name')
                                                            : \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
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
                                                                ->get()->groupBy('event_name');
                                                        ?>

                                                    {{--                                                    Qtr Table--}}
                                                    @if($event_name_qtr_regular)
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
                                                                <th class="text-center" rowspan="4"
                                                                    style="background-color: #DDD9C4; color: black; width: 10%!important;">
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

                                                    @endif

                                                    <br>
                                                        <?php
                                                        $event_name_monthly_regular = auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')
                                                            ? \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
                                                                ->whereCountryId($country->id)
                                                                ->whereEntityId($entity->id)
                                                                ->whereComplianceSubMenuId($regular_year->id)
                                                                ->where('occurrence', '=', 'Monthly')
                                                                ->where('event_type', '=', 'Add-Hoc')
                                                                ->whereAssignId(auth()->user()->id)
                                                                ->when($red, function ($query, $red) {
                                                                    if ($red == true) {
                                                                        return $query->whereStatus('Red');
                                                                    }
                                                                    return $query;
                                                                })
                                                                ->orderBy('event_name', 'asc')
                                                                ->get()->groupBy('event_name')
                                                            : \App\Models\CompliancePrimarySubMenu::whereCalendarYearId($calendar_year_id)
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
                                                                ->get()->groupBy('event_name');
                                                        ?>

                                                    {{--                                                    Monthly Table--}}
                                                    @if($event_name_monthly_regular)

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
                                                                <th class="text-center" rowspan="12"
                                                                    style="background-color: #DDD9C4; color: black; width: 10%!important;">
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
                                                    @endif
                                                @endforeach
                                            </div>


                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>


</x-filament-widgets::widget>




