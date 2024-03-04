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
{{--                            <a href="{{ route('report.dashboard-summary', [$this->calendar_year_id]) }}" style="padding: 5px;" target="_blank">--}}
{{--                                <x-filament::button >--}}
{{--                                    Download--}}
{{--                                </x-filament::button>--}}
{{--                            </a>--}}
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

                    @foreach($compliance_events as $compliance_event)
                            <?php
                            $complianceEventYear = \App\Models\ComplianceEvent::where('calendar_year_id',$this->calendar_year_id)->first();
                            ?>
                        <td class="text-center">{{$complianceEventYear->country->name ?? ''}}</td>
                        <td class="text-center">{{$complianceEventYear->name ?? ''}}</td>
                        <td class="text-center">{{$complianceEventYear->description ?? ''}}</td>
                        <td class="text-center">{{$complianceEventYear->status ?? ''}}</td>
                    @endforeach

{{--                    @foreach($countries as $country)--}}
{{--                            <?php--}}
{{--                            $uploadDocumentsPi = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('PI Cover')->first();--}}
{{--                            $uploadDocumentsFidelity = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Fidelity')->first();--}}
{{--                            $uploadDocumentsAudit = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Audited Financials')->first();--}}
{{--                            $uploadDocumentsRegistration = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Registration Documents')->first();--}}
{{--                            $uploadDocumentsLicence = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Proof of Payment')->first();--}}
{{--                            $uploadDocumentsBroker = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Broker Qtr Submissions')->first();--}}
{{--                            $uploadDocumentsAnnual = \App\Models\UploadDocument::where('country_id', $country->id)->where('calendar_year_id',$this->calendar_year_id)->whereName('Annual Return')->first();--}}
{{--                            ?>--}}
{{--                        <tr>--}}
{{--                            <td class='summary' style="width: 9%">{{$country->name}}</td>--}}
{{--                            @if($uploadDocumentsPi && $uploadDocumentsPi->is_uploaded == 1)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            @if($uploadDocumentsPi)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsPi->expired_date)->format('d-m-Y')}}</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            @if($uploadDocumentsFidelity && $uploadDocumentsFidelity->is_uploaded == 1)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            @if($uploadDocumentsFidelity)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsFidelity->expired_date)->format('d-m-Y')}}</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            @if($uploadDocumentsAudit && $uploadDocumentsAudit->is_uploaded == 1)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #add8e6;width: 3%;">YES</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            @if($uploadDocumentsAudit)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsAudit->expired_date)->format('d-m-Y')}}</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}

{{--                            @if($uploadDocumentsRegistration && $uploadDocumentsRegistration->is_uploaded == 1)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            @if($uploadDocumentsRegistration)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsRegistration->expired_date)->format('d-m-Y')}}</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            <td class='summary' style="width: 6%;font-size: 12px;">{!! $uploadDocumentsRegistration->upload_comment !!}</td>--}}

{{--                            @if($uploadDocumentsLicence && $uploadDocumentsLicence->is_uploaded == 1)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            @if($uploadDocumentsLicence)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsLicence->expired_date)->format('d-m-Y')}}</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            <td class='summary' style="width: 6%;font-size: 12px;">{!! $uploadDocumentsLicence->upload_comment !!}</td>--}}

{{--                            @if($uploadDocumentsBroker && $uploadDocumentsBroker->is_uploaded == 1)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #77ad5f;width: 3%;">YES</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            @if($uploadDocumentsBroker)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsBroker->expired_date)->format('d-m-Y')}}</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                            @if($uploadDocumentsAnnual)--}}
{{--                                <td class='summary' style="font-size: 12px;background-color: #fce4d5;width: 6%;">{{\Carbon\Carbon::parse($uploadDocumentsAnnual->expired_date)->format('d-m-Y')}}</td>--}}
{{--                            @else--}}
{{--                                <td class='summary' style="font-size: 12px;width: 3%;"></td>--}}
{{--                            @endif--}}
{{--                        </tr>--}}
{{--                    @endforeach--}}



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
