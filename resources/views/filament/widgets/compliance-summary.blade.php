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
    </style>
{{--    {{ $this->form }}--}}
    <div class="filament-tables-component" style="margin: 0; margin-top: 40px;">
        <div class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container">

            <div class="overflow-y-auto relative rounded-t-xl">
                <table class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                       style="zoom: 90%">
                    <thead>
                    <tr style="background-color: lightskyblue">
                        <th class="text-center font-bold" colspan="16"><b>Dashboard Summary (Operation)</b>

{{--                            <a href="{{ $this->calendar_year_id ? route('report.dashboard-summary', [$this->calendar_year_id]) : '#' }}"--}}
{{--                               style="padding: 5px;" target="_blank">--}}
{{--                                <x-filament::button>--}}
{{--                                    Download--}}
{{--                                </x-filament::button>--}}
{{--                            </a>--}}

                        </th>
                    </tr>

                    <tr class="custom-heading bg-gray-100 text-center" style="background-color: #ffffff;">
                        <th class="text-center">Country</th>
                        <th class="text-center">Entity</th>
                        <th class="text-center">Event Name</th>
                                                <th class="text-center">Due Date</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Compliance Type</th>
                    </tr>

                    </thead>
                    <tbody class="divide-y whitespace-nowrap">
                    @foreach($operations as $operation)
                        @php
                            $country = \App\Models\Country::find($operation);
                            $entities = \App\Models\Entity::where('country_id', $country->id)->get();
                            $totalEventsCount = $entities->sum(function ($entity) {
                                return \App\Models\CompliancePrimarySubMenu::where('entity_id', $entity->id)->count();
                            });
                        @endphp

                        @foreach($entities as $entity)
                            @php
                                $complianceSubMenuOperationIds = \App\Models\ComplianceSubMenu::where('sub_menu_name', 'Operations')
                                                                                             ->where('entity_id', $entity->id)
                                                                                             ->pluck('id')->toArray();
//                                $events = \App\Models\CompliancePrimarySubMenu::whereRelation('complianceSubMenu',function($q) use($entity){
//                                    return $q->where('sub_menu_name', 'Operations')->where('entity_id', $entity->id);
//                                })->get();
                                $events = \App\Models\CompliancePrimarySubMenu::where('entity_id', $entity->id)->get();
                                $countEntityEvents = $events->count();
                            @endphp

                            @foreach($events as $eventIndex => $event)
                               <?php
//                                    dd($eventIndex,$event)
                                ?>
                                <tr>
{{--                                    @if ($eventIndex == 0)--}}
                                        @if ($loop->parent->first && $eventIndex == 0)
                                        <td rowspan="{{ $totalEventsCount }}">
                                            {{ $country->name ?? '' }}
                                        </td>
                                    @endif

                                    @if ($eventIndex == 0)
                                        <td rowspan="{{ $countEntityEvents }}">
                                            {{ $entity->entity_name }}
                                        </td>
                                    @endif

                                    <td>{{ $event->event_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($event->due_date)->format('Y-m-d') }}</td>
                                    <td>{{ $event->status }}</td>
                                    <td>{{ $event->complianceSubMenu->sub_menu_name }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
{{--    <div class="filament-tables-component" style="margin: 0; margin-top: 40px;">--}}
{{--        <div class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container">--}}

{{--            <div class="overflow-y-auto relative rounded-t-xl">--}}
{{--                <table class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"--}}
{{--                       style="zoom: 90%">--}}
{{--                    <thead>--}}
{{--                    <tr style="background-color: lightskyblue">--}}
{{--                        <th class="text-center font-bold" colspan="16"><b>Dashboard Summary (Finance)</b>--}}

{{--                            <a href="{{ $this->calendar_year_id ? route('report.dashboard-summary', [$this->calendar_year_id]) : '#' }}"--}}
{{--                               style="padding: 5px;" target="_blank">--}}
{{--                                <x-filament::button>--}}
{{--                                    Download--}}
{{--                                </x-filament::button>--}}
{{--                            </a>--}}

{{--                        </th>--}}
{{--                    </tr>--}}

{{--                    <tr class="custom-heading bg-gray-100 text-center" style="background-color: #ffffff;">--}}
{{--                        <th class="text-center">Country</th>--}}
{{--                        <th class="text-center">Entity</th>--}}
{{--                        <th class="text-center">Event Name</th>--}}
{{--                                                <th class="text-center">Due Date</th>--}}
{{--                                                <th class="text-center">Status</th>--}}
{{--                                                <th class="text-center">Compliance Type</th>--}}
{{--                    </tr>--}}

{{--                    </thead>--}}
{{--                    <tbody class="divide-y whitespace-nowrap">--}}
{{--                    @foreach($operations as $operation)--}}
{{--                        @php--}}
{{--                            $country = \App\Models\Country::find($operation);--}}
{{--                            $entities = \App\Models\Entity::where('country_id', $country->id)->get();--}}
{{--                            $totalEventsCount = $entities->sum(function ($entity) {--}}
{{--                                return \App\Models\CompliancePrimarySubMenu::where('entity_id', $entity->id)->count();--}}
{{--                            });--}}
{{--                        @endphp--}}

{{--                        @foreach($entities as $entity)--}}
{{--                            @php--}}
{{--                                $complianceSubMenuOperationIds = \App\Models\ComplianceSubMenu::where('sub_menu_name', 'Operations')--}}
{{--                                                                                             ->where('entity_id', $entity->id)--}}
{{--                                                                                             ->pluck('id')->toArray();--}}
{{--                                $events = \App\Models\CompliancePrimarySubMenu::whereRelation('complianceSubMenu',function($q) use($entity){--}}
{{--                                    return $q->where('sub_menu_name', 'Finance')->where('entity_id', $entity->id);--}}
{{--                                })->get();--}}
{{--//                                $events = \App\Models\CompliancePrimarySubMenu::where('entity_id', $entity->id)->get();--}}
{{--                                $countEntityEvents = $events->count();--}}
{{--                            @endphp--}}

{{--                            @foreach($events as $eventIndex => $event)--}}
{{--                               <?php--}}
{{--//                                    dd($eventIndex,$event)--}}
{{--                                ?>--}}
{{--                                <tr>--}}
{{--                                    @if ($eventIndex == 0)--}}
{{--                                        @if ($loop->parent->first && $eventIndex == 0)--}}
{{--                                        <td rowspan="{{ $totalEventsCount }}">--}}
{{--                                            {{ $country->name ?? '' }}--}}
{{--                                        </td>--}}
{{--                                    @endif--}}

{{--                                    @if ($eventIndex == 0)--}}
{{--                                        <td rowspan="{{ $countEntityEvents }}">--}}
{{--                                            {{ $entity->entity_name }}--}}
{{--                                        </td>--}}
{{--                                    @endif--}}

{{--                                    <td>{{ $event->event_name }}</td>--}}
{{--                                    <td>{{ \Carbon\Carbon::parse($event->due_date)->format('Y-m-d') }}</td>--}}
{{--                                    <td>{{ $event->status }}</td>--}}
{{--                                    <td>{{ $event->complianceSubMenu->sub_menu_name }}</td>--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        @endforeach--}}
{{--                    @endforeach--}}

{{--                    </tbody>--}}
{{--                </table>--}}
{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}


</x-filament-widgets::widget>
