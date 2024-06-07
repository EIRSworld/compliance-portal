<x-filament-widgets::widget>

    {{--    <h1 style="font-weight: bold;">Dashboard Summary</h1>--}}
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
            background-color: #fcc4c4;
        }
        .amber {
            background-color: #ffeec7;
        }
        .green {
            background-color: #c4fccc;
        }
        .blue {
            background-color: #add8e6;
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
                                                <table
                                                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                                                    style="zoom: 90%">
                                                    <thead>
                                                    <tr style="background-color: lightskyblue">
{{--                                                        @dd($this->calendar_year_id)--}}
                                                        <th class="text-center font-bold" colspan="13"><b>Operation
                                                                Dashboard Summary</b>
                                                            @if($calendar_year_id && $country_id && $entity_id)
                                                                <a href="{{ route('report.dashboard-regular-operations', ['id' => $calendar_year_id, 'country_id' => $country_id,  'entity_id' => $entity_id]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id && $country_id)
                                                                <a href="{{ route('report.dashboard-regular-operations', ['id' => $calendar_year_id, 'country_id' => $country_id, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id)
                                                                <a href="{{ route('report.dashboard-regular-operations', ['id' => $calendar_year_id, 'country_id' => 0, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>

                                                            @else
                                                                <span style="padding: 5px;" class="text-gray-500 cursor-not-allowed">
                                                                    <x-filament::button disabled>Download</x-filament::button>
                                                                </span>
                                                            @endif

                                                        </th>
                                                    </tr>
                                                    <tr class="custom-heading bg-gray-100">
                                                        <th class="text-center">Country</th>
                                                        <th class="text-center">Entity</th>
                                                        <th class="text-center">Event Name</th>
                                                        <th class="text-center">Due Date</th>
                                                        <th class="text-center">Status</th>
                                                        {{--                                                        <th class="text-center">Compliance Type</th>--}}
                                                    </tr>
                                                    </thead>

                                                    <tbody class="divide-y whitespace-nowrap">

                                                    @php
                                                        $lastCountryName = null;
                                                        $lastEntityName = null;
                                                        $lastEventName = null;
                                                        $countryRowspan = 0;
                                                        $entityRowspan = 0;
                                                        $eventRowspan = 0;
                                                    @endphp

                                                    @foreach($events_regulars_operations as $events_regulars_operation)
                                                        @php
                                                            $countryRowspan = $events_regulars_operations->where('country.name', $events_regulars_operation->country->name)->count();
                                                            $entityRowspan = $events_regulars_operations->where('entity.entity_name', $events_regulars_operation->entity->entity_name)->count();
                                                            $eventRowspan = $events_regulars_operations->where('event_name', $events_regulars_operation->event_name)->count();
                                                        @endphp
                                                        <tr>
                                                            @if ($lastCountryName !== $events_regulars_operation->country->name)
                                                                <td class="text-center" rowspan="{{ $countryRowspan }}">{{$events_regulars_operation->country->name}}</td>
                                                                @php
                                                                    $lastCountryName = $events_regulars_operation->country->name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEntityName !== $events_regulars_operation->entity->entity_name)
                                                                <td class="text-center" rowspan="{{ $entityRowspan }}">{{$events_regulars_operation->entity->entity_name}}</td>
                                                                @php
                                                                    $lastEntityName = $events_regulars_operation->entity->entity_name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEventName !== $events_regulars_operation->event_name)
                                                                <td class="text-center" rowspan="{{ $eventRowspan }}">{{$events_regulars_operation->event_name}}</td>
                                                                @php
                                                                    $lastEventName = $events_regulars_operation->event_name;
                                                                @endphp
                                                            @endif

                                                            <td class="text-right"
                                                                @if($events_regulars_operation->status == 'Red')
                                                                style="background-color: #fcc4c4"
                                                                @elseif($events_regulars_operation->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_regulars_operation->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_regulars_operation->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>{{\Carbon\Carbon::parse($events_regulars_operation->due_date)->format('Y-m-d')}}</td>
                                                            <td class="text-right"
                                                                @if($events_regulars_operation->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_regulars_operation->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_regulars_operation->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_regulars_operation->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>{{$events_regulars_operation->status}}</td>
{{--                                                            <td class="text-right">{{$events_regulars_operation->complianceSubMenu->sub_menu_name}}</td>--}}
                                                        </tr>
                                                    @endforeach



                                                    </tbody>

                                                </table>
                                            </div>


                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>


{{--                    table 2--}}
                    <div class="grid grid-cols-1      filament-forms-component-container gap-6 "
                         style="margin-top: 15px;">
                        <div wire:key="hZhWt43N7BND5nGsiZ1S.one.Filament\Forms\Components\TextInput"
                             class=" col-span-1     ">
                            <div class="filament-forms-field-wrapper">

                                <div class="space-y-2">

                                    <div class="filament-tables-component">
                                        <div
                                            class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container"
                                            style="width: 100%">

                                            <div class="overflow-y-auto relative rounded-t-xl">
                                                <table class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                                                    style="zoom: 90%">
                                                    <thead>
                                                    <tr style="background-color: lightskyblue">
                                                        <th class="text-center font-bold" colspan="13"><b>Finance
                                                                Dashboard Summary</b>
                                                            @if($calendar_year_id && $country_id && $entity_id)
                                                                <a href="{{ route('report.dashboard-regular-finance', ['id' => $calendar_year_id, 'country_id' => $country_id,  'entity_id' => $entity_id]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id && $country_id)
                                                                <a href="{{ route('report.dashboard-regular-finance', ['id' => $calendar_year_id, 'country_id' => $country_id, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id)
                                                                <a href="{{ route('report.dashboard-regular-finance', ['id' => $calendar_year_id, 'country_id' => 0, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>

                                                            @else
                                                                <span style="padding: 5px;" class="text-gray-500 cursor-not-allowed">
                                                                    <x-filament::button disabled>Download</x-filament::button>
                                                                </span>
                                                            @endif
                                                        </th>
                                                    </tr>
                                                    <tr class="custom-heading bg-gray-100">
                                                        <th class="text-center">Country</th>
                                                        <th class="text-center">Entity</th>
                                                        <th class="text-center">Event Name</th>
                                                        <th class="text-center">Due Date</th>
                                                        <th class="text-center">Status</th>
                                                        {{--                                                        <th class="text-center">Compliance Type</th>--}}
                                                    </tr>
                                                    </thead>

                                                    <tbody class="divide-y whitespace-nowrap">

                                                    @php
                                                        $lastCountryName = null;
                                                        $lastEntityName = null;
                                                        $lastEventName = null;
                                                        $countryRowspan = 0;
                                                        $entityRowspan = 0;
                                                        $eventRowspan = 0;
                                                    @endphp
{{--@dd($events_regulars_finances)--}}
                                                    @foreach($events_regulars_finances as $events_regulars_finance)
                                                        @php

                                                            $countryRowspan = $events_regulars_finances->where('country.name', $events_regulars_finance->country->name)->count();
                                                            $entityRowspan = $events_regulars_finances->where('entity.entity_name', $events_regulars_finance->entity->entity_name)->count();
                                                            $eventRowspan = $events_regulars_finances->where('event_name', $events_regulars_finance->event_name)->count();
//                                                        dd($countryRowspan)
                                                        @endphp
                                                        <tr>
                                                            @if ($lastCountryName !== $events_regulars_finance->country->name)
                                                                <td class="text-center" rowspan="{{ $countryRowspan }}">{{$events_regulars_finance->country->name}}</td>
                                                                @php
                                                                    $lastCountryName = $events_regulars_finance->country->name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEntityName !== $events_regulars_finance->entity->entity_name)
                                                                <td class="text-center" rowspan="{{ $entityRowspan }}">{{$events_regulars_finance->entity->entity_name}}</td>
                                                                @php
                                                                    $lastEntityName = $events_regulars_finance->entity->entity_name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEventName !== $events_regulars_finance->event_name)
                                                                <td class="text-center" rowspan="{{ $eventRowspan }}">{{$events_regulars_finance->event_name}}</td>
                                                                @php
                                                                    $lastEventName = $events_regulars_finance->event_name;
                                                                @endphp
                                                            @endif
{{--                                                            <td class="text-left">{{$events_regulars_finance->country->name}}</td>--}}
{{--                                                            <td class="text-left">{{$events_regulars_finance->entity->entity_name}}</td>--}}
{{--                                                            <td class="text-right">{{$events_regulars_finance->event_name}}</td>--}}
                                                            <td class="text-right"
                                                                @if($events_regulars_finance->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_regulars_finance->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_regulars_finance->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_regulars_finance->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>



                                                                {{\Carbon\Carbon::parse($events_regulars_finance->due_date)->format('Y-m-d')}}</td>
                                                            <td class="text-right"
                                                                @if($events_regulars_finance->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_regulars_finance->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_regulars_finance->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_regulars_finance->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif
                                                            >{{$events_regulars_finance->status}}</td>
{{--                                                            <td class="text-right">{{$events_regulars_finance->complianceSubMenu->sub_menu_name}}</td>--}}
                                                        </tr>
{{--                                                        @dd($events_regulars_finance->status)--}}
                                                    @endforeach



                                                    </tbody>

                                                </table>
                                            </div>


                                        </div>
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>


{{-- table 3--}}
                    <div class="grid grid-cols-1      filament-forms-component-container gap-6 "
                         style="margin-top: 15px;">
                        <div wire:key="hZhWt43N7BND5nGsiZ1S.one.Filament\Forms\Components\TextInput"
                             class=" col-span-1     ">
                            <div class="filament-forms-field-wrapper">

                                <div class="space-y-2">

                                    <div class="filament-tables-component">
                                        <div
                                            class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container"
                                            style="width: 100%">

                                            <div class="overflow-y-auto relative rounded-t-xl">
                                                <table
                                                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                                                    style="zoom: 90%">
                                                    <thead>
                                                    <tr style="background-color: lightskyblue">
                                                        <th class="text-center font-bold" colspan="13"><b>HR Dashboard
                                                                Summary</b>
                                                            @if($calendar_year_id && $country_id && $entity_id)
                                                                <a href="{{ route('report.dashboard-regular-hr', ['id' => $calendar_year_id, 'country_id' => $country_id,  'entity_id' => $entity_id]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id && $country_id)
                                                                <a href="{{ route('report.dashboard-regular-hr', ['id' => $calendar_year_id, 'country_id' => $country_id, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id)
                                                                <a href="{{ route('report.dashboard-regular-hr', ['id' => $calendar_year_id, 'country_id' => 0, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>

                                                            @else
                                                                <span style="padding: 5px;" class="text-gray-500 cursor-not-allowed">
                                                                    <x-filament::button disabled>Download</x-filament::button>
                                                                </span>
                                                            @endif
                                                        </th>
                                                    </tr>
                                                    <tr class="custom-heading bg-gray-100">
                                                        <th class="text-center">Country</th>
                                                        <th class="text-center">Entity</th>
                                                        <th class="text-center">Event Name</th>
                                                        <th class="text-center">Due Date</th>
                                                        <th class="text-center">Status</th>
{{--                                                        <th class="text-center">Compliance Type</th>--}}
                                                    </tr>
                                                    </thead>

                                                    <tbody class="divide-y whitespace-nowrap">
                                                    @php
                                                        $lastCountryName = null;
                                                        $lastEntityName = null;
                                                        $lastEventName = null;
                                                        $countryRowspan = 0;
                                                        $entityRowspan = 0;
                                                        $eventRowspan = 0;
                                                    @endphp
                                                    @foreach($events_regulars_hrs as $events_regulars_hr)
                                                        @php
                                                            $countryRowspan = $events_regulars_hrs->where('country.name', $events_regulars_hr->country->name)->count();
                                                            $entityRowspan = $events_regulars_hrs->where('entity.entity_name', $events_regulars_hr->entity->entity_name)->count();
                                                            $eventRowspan = $events_regulars_hrs->where('event_name', $events_regulars_hr->event_name)->count();
                                                        @endphp
                                                        <tr>
                                                            @if ($lastCountryName !== $events_regulars_hr->country->name)
                                                                <td class="text-center" rowspan="{{ $countryRowspan }}">{{$events_regulars_hr->country->name}}</td>
                                                                @php
                                                                    $lastCountryName = $events_regulars_hr->country->name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEntityName !== $events_regulars_hr->entity->entity_name)
                                                                <td class="text-center" rowspan="{{ $entityRowspan }}">{{$events_regulars_hr->entity->entity_name}}</td>
                                                                @php
                                                                    $lastEntityName = $events_regulars_hr->entity->entity_name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEventName !== $events_regulars_hr->event_name)
                                                                <td class="text-center" rowspan="{{ $eventRowspan }}">{{$events_regulars_hr->event_name}}</td>
                                                                @php
                                                                    $lastEventName = $events_regulars_hr->event_name;
                                                                @endphp
                                                            @endif
{{--                                                            <td class="text-left">{{$events_regulars_hr->country->name}}</td>--}}
{{--                                                            <td class="text-left">{{$events_regulars_hr->entity->entity_name}}</td>--}}
{{--                                                            <td class="text-right">{{$events_regulars_hr->event_name}}</td>--}}
                                                            <td class="text-right"
                                                                @if($events_regulars_hr->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_regulars_hr->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_regulars_hr->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_regulars_hr->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>{{\Carbon\Carbon::parse($events_regulars_hr->due_date)->format('Y-m-d')}}</td>

                                                            <td class="text-right"
                                                                @if($events_regulars_hr->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_regulars_hr->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_regulars_hr->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_regulars_hr->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>{{$events_regulars_hr->status}}</td>
{{--                                                            <td class="text-right">{{$events_regulars_hr->complianceSubMenu->sub_menu_name}}</td>--}}
                                                        </tr>

                                                    @endforeach


                                                    </tbody>

                                                </table>
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

{{--                    table 4--}}
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
                                                <table
                                                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                                                    style="zoom: 90%">
                                                    <thead>
                                                    <tr style="background-color: lightskyblue">
                                                        <th class="text-center font-bold" colspan="13"><b>Operation
                                                                Dashboard Summary</b>
                                                            @if($calendar_year_id && $country_id && $entity_id)
                                                                <a href="{{ route('report.dashboard-add-hoc-operations', ['id' => $calendar_year_id, 'country_id' => $country_id,  'entity_id' => $entity_id]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id && $country_id)
                                                                <a href="{{ route('report.dashboard-add-hoc-operations', ['id' => $calendar_year_id, 'country_id' => $country_id, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id)
                                                                <a href="{{ route('report.dashboard-add-hoc-operations', ['id' => $calendar_year_id, 'country_id' => 0, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>

                                                            @else
                                                                <span style="padding: 5px;" class="text-gray-500 cursor-not-allowed">
                                                                    <x-filament::button disabled>Download</x-filament::button>
                                                                </span>
                                                            @endif
                                                        </th>
                                                    </tr>
                                                    <tr class="custom-heading bg-gray-100">
                                                        <th class="text-center">Country</th>
                                                        <th class="text-center">Entity</th>
                                                        <th class="text-center">Event Name</th>
                                                        <th class="text-center">Due Date</th>
                                                        <th class="text-center">Status</th>
                                                        {{--                                                        <th class="text-center">Compliance Type</th>--}}
                                                    </tr>
                                                    </thead>

                                                    <tbody class="divide-y whitespace-nowrap">
                                                    @php
                                                        $lastCountryName = null;
                                                        $lastEntityName = null;
                                                        $lastEventName = null;
                                                        $countryRowspan = 0;
                                                        $entityRowspan = 0;
                                                        $eventRowspan = 0;
                                                    @endphp

                                                    @foreach($events_addhocs_operations as $events_addhocs_operation)
                                                        @php
                                                            $countryRowspan = $events_addhocs_operations->where('country.name', $events_addhocs_operation->country->name)->count();
                                                            $entityRowspan = $events_addhocs_operations->where('entity.entity_name', $events_addhocs_operation->entity->entity_name)->count();
                                                            $eventRowspan = $events_addhocs_operations->where('event_name', $events_addhocs_operation->event_name)->count();
                                                        @endphp
                                                        <tr>
                                                            @if ($lastCountryName !== $events_addhocs_operation->country->name)
                                                                <td class="text-center" rowspan="{{ $countryRowspan }}">{{$events_addhocs_operation->country->name}}</td>
                                                                @php
                                                                    $lastCountryName = $events_addhocs_operation->country->name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEntityName !== $events_addhocs_operation->entity->entity_name)
                                                                <td class="text-center" rowspan="{{ $entityRowspan }}">{{$events_addhocs_operation->entity->entity_name}}</td>
                                                                @php
                                                                    $lastEntityName = $events_addhocs_operation->entity->entity_name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEventName !== $events_addhocs_operation->event_name)
                                                                <td class="text-center" rowspan="{{ $eventRowspan }}">{{$events_addhocs_operation->event_name}}</td>
                                                                @php
                                                                    $lastEventName = $events_addhocs_operation->event_name;
                                                                @endphp
                                                            @endif
{{--                                                            <td class="text-left">{{$events_addhocs_operation->country->name}}</td>--}}
{{--                                                            <td class="text-left">{{$events_addhocs_operation->entity->entity_name}}</td>--}}
{{--                                                            <td class="text-right">{{$events_addhocs_operation->event_name}}</td>--}}
                                                            <td class="text-right"
                                                                @if($events_addhocs_operation->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_addhocs_operation->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_addhocs_operation->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_addhocs_operation->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>{{\Carbon\Carbon::parse($events_addhocs_operation->due_date)->format('Y-m-d')}}</td>
                                                            <td class="text-right"
                                                                @if($events_addhocs_operation->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_addhocs_operation->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_addhocs_operation->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_addhocs_operation->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>{{$events_addhocs_operation->status}}</td>
                                                            {{--                                                            <td class="text-right">{{$events_addhocs_operation->complianceSubMenu->sub_menu_name}}</td>--}}
                                                        </tr>

                                                    @endforeach

                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

{{--table 5--}}
                    <div class="grid grid-cols-1      filament-forms-component-container gap-6"
                         style="margin-top: 15px;">
                        <div wire:key="hZhWt43N7BND5nGsiZ1S.one.Filament\Forms\Components\TextInput"
                             class=" col-span-1     ">
                            <div class="filament-forms-field-wrapper">

                                <div class="space-y-2">

                                    <div class="filament-tables-component" style="margin: 0; ">
                                        <div
                                            class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container"
                                            style="width: 100%">

                                            <div class="overflow-y-auto relative rounded-t-xl">
                                                <table
                                                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                                                    style="zoom: 90%">
                                                    <thead>
                                                    <tr style="background-color: lightskyblue">
                                                        <th class="text-center font-bold" colspan="13"><b>Finance
                                                                Dashboard Summary</b>
                                                            @if($calendar_year_id && $country_id && $entity_id)
                                                                <a href="{{ route('report.dashboard-add-hoc-finance', ['id' => $calendar_year_id, 'country_id' => $country_id,  'entity_id' => $entity_id]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id && $country_id)
                                                                <a href="{{ route('report.dashboard-add-hoc-finance', ['id' => $calendar_year_id, 'country_id' => $country_id, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id)
                                                                <a href="{{ route('report.dashboard-add-hoc-finance', ['id' => $calendar_year_id, 'country_id' => 0, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>

                                                            @else
                                                                <span style="padding: 5px;" class="text-gray-500 cursor-not-allowed">
                                                                    <x-filament::button disabled>Download</x-filament::button>
                                                                </span>
                                                            @endif
                                                          </th>
                                                    </tr>
                                                    <tr class="custom-heading bg-gray-100">
                                                        <th class="text-center">Country</th>
                                                        <th class="text-center">Entity</th>
                                                        <th class="text-center">Event Name</th>
                                                        <th class="text-center">Due Date</th>
                                                        <th class="text-center">Status</th>
                                                        {{--                                                        <th class="text-center">Compliance Type</th>--}}
                                                    </tr>
                                                    </thead>

                                                    <tbody class="divide-y whitespace-nowrap">
                                                    @php
                                                        $lastCountryName = null;
                                                        $lastEntityName = null;
                                                        $lastEventName = null;
                                                        $countryRowspan = 0;
                                                        $entityRowspan = 0;
                                                        $eventRowspan = 0;
                                                    @endphp

                                                    @foreach($events_addhocs_finances as $events_addhocs_finance)
                                                        @php
                                                            $countryRowspan = $events_addhocs_finances->where('country.name', $events_addhocs_finance->country->name)->count();
                                                            $entityRowspan = $events_addhocs_finances->where('entity.entity_name', $events_addhocs_finance->entity->entity_name)->count();
                                                            $eventRowspan = $events_addhocs_finances->where('event_name', $events_addhocs_finance->event_name)->count();
                                                        @endphp
                                                        <tr>
                                                            @if ($lastCountryName !== $events_addhocs_finance->country->name)
                                                                <td class="text-center" rowspan="{{ $countryRowspan }}">{{$events_addhocs_finance->country->name}}</td>
                                                                @php
                                                                    $lastCountryName = $events_addhocs_finance->country->name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEntityName !== $events_addhocs_finance->entity->entity_name)
                                                                <td class="text-center" rowspan="{{ $entityRowspan }}">{{$events_addhocs_finance->entity->entity_name}}</td>
                                                                @php
                                                                    $lastEntityName = $events_addhocs_finance->entity->entity_name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEventName !== $events_addhocs_finance->event_name)
                                                                <td class="text-center" rowspan="{{ $eventRowspan }}">{{$events_addhocs_finance->event_name}}</td>
                                                                @php
                                                                    $lastEventName = $events_addhocs_finance->event_name;
                                                                @endphp
                                                            @endif
{{--                                                            <td class="text-left">{{$events_addhocs_finance->country->name}}</td>--}}
{{--                                                            <td class="text-left">{{$events_addhocs_finance->entity->entity_name}}</td>--}}
{{--                                                            <td class="text-right">{{$events_addhocs_finance->event_name}}</td>--}}
                                                            <td class="text-right"
                                                                @if($events_addhocs_finance->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_addhocs_finance->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_addhocs_finance->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_addhocs_finance->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>{{\Carbon\Carbon::parse($events_addhocs_finance->due_date)->format('Y-m-d')}}</td>
                                                            <td class="text-right"
                                                                @if($events_addhocs_finance->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_addhocs_finance->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_addhocs_finance->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_addhocs_finance->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>{{$events_addhocs_finance->status}}</td>
                                                            {{--                                                            <td class="text-right">{{$events_addhocs_finance->complianceSubMenu->sub_menu_name}}</td>--}}
                                                        </tr>

                                                    @endforeach

                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

{{--table 6--}}
                    <div class="grid grid-cols-1      filament-forms-component-container gap-6"
                         style="margin-top: 15px;">
                        <div wire:key="hZhWt43N7BND5nGsiZ1S.one.Filament\Forms\Components\TextInput"
                             class=" col-span-1     ">
                            <div class="filament-forms-field-wrapper">

                                <div class="space-y-2">

                                    <div class="filament-tables-component" style="margin: 0; ">
                                        <div
                                            class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container"
                                            style="width: 100%">

                                            <div class="overflow-y-auto relative rounded-t-xl">
                                                <table
                                                    class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                                                    style="zoom: 90%">
                                                    <thead>
                                                    <tr style="background-color: lightskyblue">
                                                        <th class="text-center font-bold" colspan="13"><b>HR Dashboard
                                                                Summary</b>
                                                            @if($calendar_year_id && $country_id && $entity_id)
                                                                <a href="{{ route('report.dashboard-add-hoc-hr', ['id' => $calendar_year_id, 'country_id' => $country_id,  'entity_id' => $entity_id]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id && $country_id)
                                                                <a href="{{ route('report.dashboard-add-hoc-hr', ['id' => $calendar_year_id, 'country_id' => $country_id, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>
                                                            @elseif($calendar_year_id)
                                                                <a href="{{ route('report.dashboard-add-hoc-hr', ['id' => $calendar_year_id, 'country_id' => 0, 'entity_id' => 0]) }}" style="padding: 5px;" target="_blank">
                                                                    <x-filament::button>Download</x-filament::button>
                                                                </a>

                                                            @else
                                                                <span style="padding: 5px;" class="text-gray-500 cursor-not-allowed">
                                                                    <x-filament::button disabled>Download</x-filament::button>
                                                                </span>
                                                            @endif</th>
                                                    </tr>
                                                    <tr class="custom-heading bg-gray-100">
                                                        <th class="text-center">Country</th>
                                                        <th class="text-center">Entity</th>
                                                        <th class="text-center">Event Name</th>
                                                        <th class="text-center">Due Date</th>
                                                        <th class="text-center">Status</th>
                                                        {{--                                                        <th class="text-center">Compliance Type</th>--}}
                                                    </tr>
                                                    </thead>

                                                    <tbody class="divide-y whitespace-nowrap">
                                                    @php
                                                        $lastCountryName = null;
                                                        $lastEntityName = null;
                                                        $lastEventName = null;
                                                        $countryRowspan = 0;
                                                        $entityRowspan = 0;
                                                        $eventRowspan = 0;
                                                    @endphp

                                                    @foreach($events_addhocs_hrs as $events_addhocs_hr)
                                                        @php
                                                            $countryRowspan = $events_addhocs_hrs->where('country.name', $events_addhocs_hr->country->name)->count();
                                                            $entityRowspan = $events_addhocs_hrs->where('entity.entity_name', $events_addhocs_hr->entity->entity_name)->count();
                                                            $eventRowspan = $events_addhocs_hrs->where('event_name', $events_addhocs_hr->event_name)->count();
                                                        @endphp
                                                        <tr>
                                                            @if ($lastCountryName !== $events_addhocs_hr->country->name)
                                                                <td class="text-center" rowspan="{{ $countryRowspan }}">{{$events_addhocs_hr->country->name}}</td>
                                                                @php
                                                                    $lastCountryName = $events_addhocs_hr->country->name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEntityName !== $events_addhocs_hr->entity->entity_name)
                                                                <td class="text-center" rowspan="{{ $entityRowspan }}">{{$events_addhocs_hr->entity->entity_name}}</td>
                                                                @php
                                                                    $lastEntityName = $events_addhocs_hr->entity->entity_name;
                                                                @endphp
                                                            @endif

                                                            @if ($lastEventName !== $events_addhocs_hr->event_name)
                                                                <td class="text-center" rowspan="{{ $eventRowspan }}">{{$events_addhocs_hr->event_name}}</td>
                                                                @php
                                                                    $lastEventName = $events_addhocs_hr->event_name;
                                                                @endphp
                                                            @endif
{{--                                                            <td class="text-left">{{$events_addhocs_hr->country->name}}</td>--}}
{{--                                                            <td class="text-left">{{$events_addhocs_hr->entity->entity_name}}</td>--}}
{{--                                                            <td class="text-right">{{$events_addhocs_hr->event_name}}</td>--}}
                                                            <td class="text-right"
                                                                @if($events_addhocs_hr->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_addhocs_hr->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_addhocs_hr->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_addhocs_hr->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>{{\Carbon\Carbon::parse($events_addhocs_hr->due_date)->format('Y-m-d')}}</td>
                                                            <td class="text-right"
                                                                @if($events_addhocs_hr->status == 'Red')
                                                                    style="background-color: #fcc4c4"
                                                                @elseif($events_addhocs_hr->status == 'Amber')
                                                                    style="background-color: #ffeec7"
                                                                @elseif($events_addhocs_hr->status == 'Green')
                                                                    style="background-color: #ccffd4"
                                                                @elseif($events_addhocs_hr->status == 'Blue')
                                                                    style="background-color: #add8e6"
                                                                @endif>{{$events_addhocs_hr->status}}</td>
                                                            {{--                                                            <td class="text-right">{{$events_addhocs_hr->complianceSubMenu->sub_menu_name}}</td>--}}
                                                        </tr>

                                                    @endforeach

                                                    </tbody>

                                                </table>
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
