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
    {{ $this->form }}
    <div class="filament-tables-component" style="margin: 0; margin-top: 10px;">
        <div class="border border-gray-300 shadow-sm bg-white rounded-xl filament-tables-container">

            <div class="overflow-y-auto relative rounded-t-xl">
                <table class="w-full text-left rtl:text-right divide-y table-auto filament-tables-table"
                       style="zoom: 90%">
                    <thead>
                    <tr style="background-color: lightskyblue">
                        <th class="text-center font-bold" colspan="16"><b>Dashboard Event Summary</b>
                                                        <a href="{{ route('report.dashboard-event-summary', [$this->calendar_year_id]) }}" style="padding: 5px;" target="_blank">
                                                            <x-filament::button >
                                                                Download
                                                            </x-filament::button>
                                                        </a>
                        </th>
                    </tr>

                    <tr class="custom-heading bg-gray-100 text-center" style="background-color: #ffffff;">
                        <th class="text-center">Country</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Status</th>
                    </tr>

                    </thead>
                    <tbody class="divide-y whitespace-nowrap">

                    @foreach($compliance_events->where('calendar_year_id', $this->calendar_year_id) as $compliance_event)
                        @if($compliance_event->status == 'Red')
                            <tr style="background-color: #f39393;color:black;">
                                <td class="text-center">{{$compliance_event->country->name ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->name ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->description ?? ''}}</td>
                                <td class="text-center">{{$compliance_event->status ?? ''}}</td>
                            </tr>
                        @elseif($compliance_event->status == 'Amber')
                            <tr style="background-color: #efefaa;color:black;">
                            <td class="text-center">{{$compliance_event->country->name ?? ''}}</td>
                            <td class="text-center">{{$compliance_event->name ?? ''}}</td>
                            <td class="text-center">{{$compliance_event->description ?? ''}}</td>
                            <td class="text-center">{{$compliance_event->status ?? ''}}</td>
                        </tr>
                        @elseif($compliance_event->status == 'Green')
                            <tr style="background-color: #bdfcbd;color:black;">
                            <td class="text-center">{{$compliance_event->country->name ?? ''}}</td>
                            <td class="text-center">{{$compliance_event->name ?? ''}}</td>
                            <td class="text-center">{{$compliance_event->description ?? ''}}</td>
                            <td class="text-center">{{$compliance_event->status ?? ''}}</td>
                        </tr>
                        @elseif($compliance_event->status == 'Blue')
                            <tr style="background-color: #a5a5ff;color:black;">
                            <td class="text-center">{{$compliance_event->country->name ?? ''}}</td>
                            <td class="text-center">{{$compliance_event->name ?? ''}}</td>
                            <td class="text-center">{{$compliance_event->description ?? ''}}</td>
                            <td class="text-center">{{$compliance_event->status ?? ''}}</td>
                        </tr>

                        @endif

                    @endforeach


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
